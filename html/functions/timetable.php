<?php
    $class_num;
    function get_timetable($grade, $class, $day){
        header('Content-type: application/json; charset=UTF-8');
        require("Snoopy.class.php");
        $URL = "http://comcigan.com:4082/st";
        $snoopy = new Snoopy; // snoopy 생성
        $snoopy->fetch($URL);
        preg_match("/window\.localStorage;.*var [A-z0-9ㄱ-ㅎ가-힣$_]+\s*=\s*'([\w_-]+)'/", $snoopy->results, $code);
        // https://github.com/Devonnuri/gongjujung-chatbot/blob/master/src/Parser.js 참고
        $URL = "http://comcigan.com:4082/$code[1]?MTc1OV81MzUxMF8xXzA=";
        $snoopy->fetch($URL);
        $jsonData = stripslashes(html_entity_decode($snoopy->results));
        $json=json_decode(trim($jsonData),true);
        // print_r($json); echo "\n";
        $teacher; //교사명을 저장할 배열
        $teacher_idx=$json['교사수']; //교사 수
        $count = 0;
        foreach ($json['성명'] as $key => $value) {
            //교사명
            if ($count <= $teacher_idx){
              $teacher[$count] = $value;
            }
            $count++;
        }
        $subject; //과목명을 저장할 배열
        $subject_idx=$json['과목명'][0]; // 과목 수
        $count = 0;
        foreach ($json['과목명'] as $key => $value) {
            //과목명
            if ($count <= $subject_idx){
              $subject[$count] = $value;
            }
            $count++;
        }
        global $class_num;
        $count = 0;
        foreach ($json['학급수'] as $key => $value) {
            //과목명
            if ($count <= 3){
              $class_num[$count] = $value;
            }
            $count++;
        }
        $table_ord; //원래 시간표 인덱스를 저장할 배열(변경 x)
        $count = 0;
        foreach ($json['시간표'][$grade][$class][$day] as $key => $value) {
            // echo $value%100 . "\n"; //과목 인덱스
            // echo $value/100 . "\n"; //교사명 인덱스
            if (0<$count && 7>$count) {
              $table_ord[$count] = $value%100;
            }
            $count++;
        }
        $count = 0; $idx = 0; // count(1~6), idx(0~5)
        $return;
        if ($day>5 || $day==0){ //범위 초과
          $return = "시간표가 없습니다.";
        }
        else{
            $final; // 요청받은 날짜의 시간표 + 변경된 시간표가 있으면 표시
            foreach ($json['학급시간표'][$grade][$class][$day] as $key => $value) {
                if (0<$count && 7>$count) {
                  if ($value%100 !== $table_ord[$count]){
                    $final[$idx] = '*'; // 시간표 변경시 '*'으로 표시
                  }
                  $final[$idx] = $final[$idx] . $subject[$value%100];
                  $final[$idx] = $final[$idx] . '(' . $teacher[$value/100] . ")";
                  if ($count !== 6) $final[$idx] = $final[$idx] . "\\n";
                }
                $count++; $idx++;
            }
            foreach ($final as $key => $value) {
                $return = $return . $value;
            }
        }
        return $return;
    }
    // usage:
    // get_timetable(3, 1, 3); //3학년 1반의 수요일(3) 시간표
    function keyboard_grade($content){
        //$content에서 숫자 추출, 학년별 키보드 출력
        get_timetable(3, 1, 1);
        global $class_num;
        $grade = preg_replace("/[^0-9]*/s", "", $content);
        $buttons;
        $count = 0;
        for ($i=1; $i <= $class_num[$grade]; $i++) {
            $buttons[$count] = $grade . "학년 " . $i . "반";
            $count++;
        }
        $buttons[$count++] = "처음으로";
        start_echo();
            start_msg();
                echo_text("학급을 선택해줘!\\n(가끔씩 서버 에러가 뜰 수 있으니까 주의해줘)", 0);
            end_msg(1);
            keyboard_button($buttons);
        end_echo();
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
        start_echo();
            start_msg();
                echo_text("언제 시간표가 필요해?", 0);
            end_msg(1);
            keyboard_button($buttons);
        end_echo();
    }
    function keyboard_date($content, $date){
        preg_match_all('/[[:alnum:]]/', $content, $match);
        $grade = $match[0][0];
        $class = $match[0][1];
        $result;
        if ($date == 0){ // 오늘
            $result = get_timetable($grade, $class, date('w'));
        }
        else if ($date !== 0){ // 내일 or 내일 모레 (not 오늘)
            $day=0; // weekday number를 가져옴
            if ( date('w')+$date > 6) {
                $day = (date('w')+$date)-7;
            } else {
                $day = date('w')+$date;
            }
            $result = get_timetable($grade, $class, $day);
        }
        $buttons[0] = $grade . "학년 " . $class . "반 (오늘)";
        $buttons[1] = $grade . "학년 " . $class . "반 (내일)";
        $buttons[2] = $grade . "학년 " . $class . "반 (모레)";
        $buttons[3] = "처음으로";
        start_echo();
            start_msg();
                echo_text($result, 0);
            end_msg(1);
            keyboard_button($buttons);
        end_echo();
    }
?>
