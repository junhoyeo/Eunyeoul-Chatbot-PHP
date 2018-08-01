<?php
    $class_num[1] = 6;
    $class_num[2] = 5;
    $class_num[3] = 5;
    function get_timetable($user_key, $grade, $class, $day){
        writelog($user_key, "사용자가 " . $day . "일 뒤의 " . $grade . "학년 " . $class . "반 기본 시간표를 확인했습니다.");                    
        $table_today = $grade . "학년 " . $class . "반 기본 시간표야!\\n";
        // 만약 요일이 일요일(0)이거나 토요일(6)이면 수업 없음을 출력하고 끝내면 되고
        // 아니라면 파일에서 해당 요일의 기본 시간표를 가져와야 함
        // n요일이라고 하면 n*6-6+1줄에서 n*6줄까지 읽어오면 됨
        if ( $day == 0 || $day == 6 ){ // 일요일이나 토요일
            $table_today = $table_today . "\\n수업이 없습니다.";
        }
        else { // 평일
            $count = 0;
            $filename = "./timetable-data/grade" . $grade . "/" . $class . ".data";
            $handle = fopen($filename, "r");
            while (($line = fgets($handle)) !== false) {
                if ($day*6-7 < $count && $count < $day*6){
                    $line = substr($line, 0, -1);
                    $table_today = $table_today . "\\n" . $line;
                }
                $count++;
            }
            fclose($handle);
        }
        return $table_today;
    }
    // usage:
    // get_timetable(3, 1, 3); //3학년 1반의 수요일(3) 시간표
    function keyboard_grade($content){
        //$content에서 숫자 추출, 학년별 키보드 출력
        // get_timetable(3, 1, 1);
        global $class_num;
        $grade = preg_replace("/[^0-9]*/s", "", $content);
        $buttons;
        $count = 0;
        for ($i=1; $i <= $class_num[$grade]; $i++) {
            $buttons[$count] = $grade . "학년 " . $i . "반";
            $count++;
        }
        $buttons[$count++] = "처음으로";
        echo json_encode(
            array(
                'message' => array(
                    'text' => '학급을 선택해줘!\\n(가끔씩 서버 에러가 뜰 수 있으니까 주의해줘)'
                ),
                'keyboard' => array(
                    'type' => 'buttons',
                    'buttons' => $buttons
                )
            )
        );
    }
    function keyboard_class($content){
        // $content에서 숫자(학년+반) 추출, 날짜별 키보드 출력
        preg_match_all('/[[:alnum:]]/', $content, $match);
        $grade = $match[0][0];
        $class = $match[0][1];
        $buttons[0] = $grade . "학년 " . $class . "반 (오늘)";
        $buttons[1] = $grade . "학년 " . $class . "반 (내일)";
        $buttons[2] = $grade . "학년 " . $class . "반 (모레)";
        $buttons[3] = "처음으로";
        echo json_encode(
            array(
                'message' => array(
                    'text' => '언제 시간표가 필요해?'
                ),
                'keyboard' => array(
                    'type' => 'buttons',
                    'buttons' => $buttons
                )
            )
        );
    }
    function keyboard_date($user_key, $content, $date){
        preg_match_all('/[[:alnum:]]/', $content, $match);
        $grade = $match[0][0];
        $class = $match[0][1];
        $result;
        if ($date == 0){ // 오늘
            $result = get_timetable($user_key, $grade, $class, date('w'));
        }
        else if ($date !== 0){ // 내일 or 내일 모레 (not 오늘)
            $day=0; // weekday number를 가져옴
            if ( date('w')+$date > 6) {
                $day = (date('w')+$date)-7;
            } else {
                $day = date('w')+$date;
            }
            $result = get_timetable($user_key, $grade, $class, $day);
        }
        $buttons[0] = $grade . "학년 " . $class . "반 (오늘)";
        $buttons[1] = $grade . "학년 " . $class . "반 (내일)";
        $buttons[2] = $grade . "학년 " . $class . "반 (모레)";
        $buttons[3] = "처음으로";
        echo json_encode(
            array(
                'message' => array(
                    'text' => $result
                ),
                'keyboard' => array(
                    'type' => 'buttons',
                    'buttons' => $buttons
                )
            )
        );
    }
?>
