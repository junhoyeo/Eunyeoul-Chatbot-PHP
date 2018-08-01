<?php
    include("functions/meal.php");
    include("functions/weather.php");
    include("functions/lol.php");
    include("functions/pubg.php");
    include("functions/maple.php");
    include("functions/timetable.php");
    $data = json_decode(file_get_contents('php://input'), true);
    $content = $data["content"];
    $user_key = $data["user_key"];

    //최우선적으로 처리되는 부분 => 1 depth 이상의 chat인지 확인
    if (file_exists("userkey/" . $user_key . ".txt")) {
        $keyfile = fopen("userkey/" . $user_key . ".txt", 'r') or die();
        $last_content = fgets($keyfile);
        $last_content = str_replace("\n", '', $last_content);
        fclose($keyfile);
        // 해당 user key를 가진 사람의 최근 채팅 기록을 확인
        if (strcmp($last_content, "League of Legends") == false){
            //이전에 리그오브레전드 전적을 조회하기로 했을 경우, $content는 검색하려는 소환사명일 것
            lol($content);
            writelog($user_key, "사용자가 " . $content . "의 리그오브레전드 전적을 확인했습니다.");            
            unlink("userkey/" . $user_key . ".txt"); // user key 파일 삭제
            return;
        }
        else if (strcmp($last_content, "Maplestory") == false){
            //이전에 메이플스토리 스탯을 조회하기로 했을 경우, $content는 검색하려는 캐릭터 이름일 것
            maple($content);
            writelog($user_key, "사용자가 " . $content . "의 메이플스토리 스탯을 확인했습니다.");                        
            unlink("userkey/" . $user_key . ".txt"); // user key 파일 삭제
            return;
        }
    }

    if ( strcmp($content, "대화 시작") == false ) {
        writelog($user_key, "사용자가 대화를 시작했습니다.");
        echo json_encode(
            array(
                'message' => array(
                    'text' => '안녕! 나는 은여울중학교 급식봇이야! ><',
                    'photo' => array(
                        'url' => 'http://silvermealbot.dothome.co.kr/images/logo.jpg',
                        'width' => 600,
                        'height' => 600
                    )
                ),
                'keyboard' => array(
                    'type' => 'buttons',
                    'buttons' => array(
                        '급식', '날씨', '시간표', '게임 전적', '정보'
                    )
                )
            )
        );
    }
    else if ( strcmp($content, "오늘 급식") == false ) {
        writelog($user_key, "사용자가 오늘 급식을 조회했습니다.");
        $final = getmeal(0);
        echo json_encode(
            array(
                'message' => array(
                    'text' => $final[0] . '\\n은여울중학교 급식 정보야!\\n\\n' . $final[1],
                ),
                'keyboard' => array(
                    'type' => 'buttons',
                    'buttons' => array(
                        '오늘 급식', '내일 급식', '내일 모레 급식', '처음으로'
                    )
                )
            )
        );
    }
    else if ( strcmp($content, "내일 급식") == false ) {
        writelog($user_key, "사용자가 내일 급식을 조회했습니다.");        
        $final = getmeal(1);
        echo json_encode(
            array(
                'message' => array(
                    'text' => $final[0] . '\\n은여울중학교 급식 정보야!\\n\\n' . $final[1],
                ),
                'keyboard' => array(
                    'type' => 'buttons',
                    'buttons' => array(
                        '오늘 급식', '내일 급식', '내일 모레 급식', '처음으로'
                    )
                )
            )
        );
    }
    else if ( strcmp($content, "내일 모레 급식") == false ) {
        writelog($user_key, "사용자가 내일 모레 급식을 조회했습니다.");
        $final = getmeal(2);
        echo json_encode(
            array(
                'message' => array(
                    'text' => $final[0] . '\\n은여울중학교 급식 정보야!\\n\\n' . $final[1],
                ),
                'keyboard' => array(
                    'type' => 'buttons',
                    'buttons' => array(
                        '오늘 급식', '내일 급식', '내일 모레 급식', '처음으로'
                    )
                )
            )
        );
    }
    else if ( strcmp($content, "급식") == false ) {
        echo json_encode(
            array(
                'message' => array(
                    'text' => '언제 급식을 알고 싶어?'
                ),
                'keyboard' => array(
                    'type' => 'buttons',
                    'buttons' => array(
                        '오늘 급식', '내일 급식', '내일 모레 급식', '처음으로'
                    )
                )
            )
        );
    }
    else if ( strcmp($content, "날씨") == false ) {
        writelog($user_key, "사용자가 오늘 날씨를 조회했습니다.");        
        $final = weather();
        echo json_encode(
            array(
                'message' => array(
                    'text' => $final[0],
                    'photo' => array(
                        'url' => $final[1],
                        'width' => 600,
                        'height' => 600
                    )
                ),
                'keyboard' => array(
                    'type' => 'buttons',
                    'buttons' => array(
                        '급식', '날씨', '시간표', '게임 전적', '정보'
                    )
                )
            )
        );
    }
    else if ( strcmp($content, "정보") == false ) {
        writelog($user_key, "사용자가 정보를 확인했습니다.");        
        echo json_encode(
            array(
                'message' => array(
                    'text' => '은여울중학교 급식봇은 은여울중학교 급식 정보(나이스 학생서비스), 시간별 날씨(기상청 RSS, 김포시 구래동 기상대 기준) 그리고 일부 게임 전적과 전학급 기본 시간표를 제공하고 있어.\\n2018.30116'
                ),
                'keyboard' => array(
                    'type' => 'buttons',
                    'buttons' => array(
                        '후원하기', '처음으로'
                    )
                )
            )
        );
    }
    else if ( strcmp($content, "후원하기") == false ) {
        writelog($user_key, "사용자가 기부 수단을 확인했습니다."); 
        echo json_encode(
            array(
                'message' => array(
                    'text' => (
                        '계좌이체 : KB국민 818702-00-018145\\n'.
                        'BTC : 1HnC2Y4tbNgcoErCcoZcmsnRzqcT5rdWon\\n'.
                        'ETH : 0x07B8CedbE8Ab83F06DFAdC39991910A4544dE3A1\\n'.
                        '비트코인 캐시 : qzuqmmmdxw5l00fjf7nzl7ur3jv2yr9vfv7f62trc0'
                    )
                ),
                'keyboard' => array(
                    'type' => 'buttons',
                    'buttons' => array(
                        '급식', '날씨', '시간표', '게임 전적', '정보'
                    )
                )
            )
        );
    }
    else if ( strcmp($content, "처음으로") == false ) {
        echo json_encode(
            array(
                'message' => array(
                    'text' => '처음으로 돌아왔습니다.'
                ),
                'keyboard' => array(
                    'type' => 'buttons',
                    'buttons' => array(
                        '대화 시작'
                    )
                )
            )
        );
    }
    else if ( strcmp($content, "게임 전적") == false ) {
        echo json_encode(
            array(
                'message' => array(
                    'text' => '게임 전적을 확인할 게임을 선택해줘~'
                ),
                'keyboard' => array(
                    'type' => 'buttons',
                    'buttons' => array(
                        'League of Legends', 'PUBG', 'Maplestory', '처음으로'
                    )
                )
            )
        );
    }
    else if ( strcmp($content, "League of Legends") == false ) {
        $keyfile = fopen("userkey/" . $user_key . ".txt", 'w') or die();
        fwrite($keyfile, $content . "\n");
        fclose($keyfile); // 해당 user key를 가진 사람의 최근 대화를 기록
        echo json_encode(
            array(
                'message' => array(
                    'text' => '검색할 소환사명을 입력해줘!'
                )
            )
        );
    }
    else if ( strcmp($content, "PUBG") == false ) {
        writelog($user_key, "사용자가 배틀그라운드 전적 조회를 시도했습니다.");
        echo json_encode(
            array(
                'message' => array(
                    'text' => '배그 전적은 아직 개발중이야'
                ),
                'keyboard' => array(
                    'type' => 'buttons',
                    'buttons' => array(
                        'League of Legends', 'PUBG', 'Maplestory', '처음으로'
                    )
                )
            )
        );
    }
    else if ( strcmp($content, "Maplestory") == false ) {
        $keyfile = fopen("userkey/" . $user_key . ".txt", 'w') or die();
        fwrite($keyfile, $content . "\n");
        fclose($keyfile); // 해당 user key를 가진 사람의 최근 대화를 기록
        echo json_encode(
            array(
                'message' => array(
                    'text' => '검색할 캐릭터 이름을 입력해줘!'
                )
            )
        );
    }
    else if ( strcmp($content, "시간표") == false ) {
        echo json_encode(
            array(
                'message' => array(
                    'text' => '몇 학년이야?'
                ),
                'keyboard' => array(
                    'type' => 'buttons',
                    'buttons' => array(
                        '1학년', '2학년', '3학년', '처음으로'
                    )
                )
            )
        );
    }
    else if ( strcmp($content, "1학년") == false || strcmp($content, "2학년") == false || strcmp($content, "3학년") == false) {
        keyboard_grade($content);
    }
    else if ( (strpos($content, "학년") !== false) && (strpos($content, "(") == false)) {
        keyboard_class($content);
    }
    else if ( (strpos($content, "반 (오늘)") !== false) ) {
        keyboard_date($user_key, $content, 0);
    }
    else if ( (strpos($content, "반 (내일)") !== false) ) {
        keyboard_date($user_key, $content, 1);
    }
    else if ( (strpos($content, "반 (모레)") !== false) ) {
        keyboard_date($user_key, $content, 2);
    }
    else{
        echo json_encode(
            array(
                'message' => array(
                    'text' => '에러가 발생햇오요,,,끼야악'
                ),
                'keyboard' => array(
                    'type' => 'buttons',
                    'buttons' => array(
                        '급식', '날씨', '시간표', '게임 전적', '정보'
                    )
                )
            )
        );
    }
?>
