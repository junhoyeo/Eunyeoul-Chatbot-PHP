<?php
    include("functions/echoKakao.php");
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
        start_echo();
            start_msg();
                echo_text("안녕! 나는 은여울중학교 급식봇이야! ><", 1);
                echo_photo("http://silvermealbot.dothome.co.kr/images/logo.jpg", 600, 600, 0);
            end_msg(1);
            keyboard_button(array("급식", "날씨", "시간표", "게임 전적", "정보"));
        end_echo();
    }
    else if ( strcmp($content, "오늘 급식") == false ) {
        writelog($user_key, "사용자가 오늘 급식을 조회했습니다.");
        $final = getmeal(0);
        start_echo();
            start_msg();
                echo_text($final[0] . "\\n은여울중학교 급식 정보야!\\n\\n" . $final[1], 0);
            end_msg(1);
            keyboard_button(array("오늘 급식", "내일 급식", "내일 모레 급식", "처음으로"));
        end_echo();
    }
    else if ( strcmp($content, "내일 급식") == false ) {
        writelog($user_key, "사용자가 내일 급식을 조회했습니다.");        
        $final = getmeal(1);
        start_echo();
            start_msg();
                echo_text($final[0] . "\\n은여울중학교 급식 정보야!\\n\\n" . $final[1], 0);
            end_msg(1);
            keyboard_button(array("오늘 급식", "내일 급식", "내일 모레 급식", "처음으로"));
        end_echo();
    }
    else if ( strcmp($content, "내일 모레 급식") == false ) {
        writelog($user_key, "사용자가 내일 모레 급식을 조회했습니다.");        
        $final = getmeal(2);
        start_echo();
            start_msg();
                echo_text($final[0] . "\\n은여울중학교 급식 정보야!\\n\\n" . $final[1], 0);
            end_msg(1);
            keyboard_button(array("오늘 급식", "내일 급식", "내일 모레 급식", "처음으로"));
        end_echo();
    }
    else if ( strcmp($content, "급식") == false ) {
        start_echo();
            start_msg();
                echo_text("언제 급식을 알고 싶어?", 0);
            end_msg(1);
            keyboard_button(array("오늘 급식", "내일 급식", "내일 모레 급식", "처음으로"));
        end_echo();
    }
    else if ( strcmp($content, "날씨") == false ) {
        writelog($user_key, "사용자가 오늘 날씨를 조회했습니다.");        
        $final = weather();
        start_echo();
            start_msg();
                echo_text($final[0], 1);
                echo_photo($final[1], 600, 600, 0);
            end_msg(1);
            keyboard_button(array("급식", "날씨", "시간표", "게임 전적", "정보"));
        end_echo();
    }
    else if ( strcmp($content, "정보") == false ) {
        writelog($user_key, "사용자가 정보를 확인했습니다.");        
        start_echo();
            start_msg();
                echo_text("아까도 말했듯이 나는 은여울중학교의 급식봇이야!\\n" .
                "급식 데이터를 교육청 페이지에서 파싱해와서 알려주는거지.\\n" .
                "내가 더 성장할 수 있도록 개발자 기부를 통해서 후원해주면 고맙겠엉!!\\n" .
                "아 그리고 날씨, 시간표, 게임 등 다양한 데이터도 같이 제공해 주고 있어ㅎㅎ", 1);
                echo_photo("http://silvermealbot.dothome.co.kr/images/gibu.jpg", 600, 650, 0);
            end_msg(1);
            keyboard_button(array("기부하기", "처음으로"));
        end_echo();
    }
    else if ( strcmp($content, "기부하기") == false ) {
        writelog($user_key, "사용자가 기부 수단을 확인했습니다.");        
        start_echo();
            start_msg();
                echo_text("다양한 방법으로 나를 후원할 수 있어!!\\n" .
                "계좌이체 : 국민은행 818702-00-018145 여준호\\n" .
                "비트코인 : 1HnC2Y4tbNgcoErCcoZcmsnRzqcT5rdWon\\n" .
                "이더리움 : 0x07B8CedbE8Ab83F06DFAdC39991910A4544dE3A1\\n" .
                "비트코인 캐시 : qzuqmmmdxw5l00fjf7nzl7ur3jv2yr9vfv7f62trc0\\n" .
                "다른 거래 수단을 원한다면 개발자에게 따로 말해주면 도와줄 수 있을 거야!", 0);
            end_msg(1);
            keyboard_button(array("급식", "날씨", "시간표", "게임 전적", "정보"));
        end_echo();
    }
    else if ( strcmp($content, "처음으로") == false ) {
        start_echo();
            start_msg();
                echo_text("처음으로 돌아왔습니다.", 0);
            end_msg(1);
            keyboard_button(array("대화 시작"));
        end_echo();
    }
    else if ( strcmp($content, "게임 전적") == false ) {
        start_echo();
            start_msg();
                echo_text("게임 전적을 확인할 게임을 선택해줘~", 0);
            end_msg(1);
            keyboard_button(array("League of Legends", "PUBG", "Maplestory", "처음으로"));
        end_echo();
    }
    else if ( strcmp($content, "League of Legends") == false ) {
        $keyfile = fopen("userkey/" . $user_key . ".txt", 'w') or die();
        fwrite($keyfile, $content . "\n");
        fclose($keyfile); // 해당 user key를 가진 사람의 최근 대화를 기록
        start_echo();
            start_msg();
                echo_text("검색할 소환사명을 입력해줘!", 0);
            end_msg(0);
        end_echo();
    }
    else if ( strcmp($content, "PUBG") == false ) {
        writelog($user_key, "사용자가 배틀그라운드 전적 조회를 시도했습니다.");        
        start_echo();
            start_msg();
                echo_text("배그 전적은 아직 개발중이야", 0);
            end_msg(1);
            keyboard_button(array("League of Legends", "PUBG", "Maplestory", "처음으로"));
        end_echo();
    }
    else if ( strcmp($content, "Maplestory") == false ) {
        $keyfile = fopen("userkey/" . $user_key . ".txt", 'w') or die();
        fwrite($keyfile, $content . "\n");
        fclose($keyfile); // 해당 user key를 가진 사람의 최근 대화를 기록
        start_echo();
            start_msg();
                echo_text("검색할 캐릭터 이름을 입력해줘!", 0);
            end_msg(0);
        end_echo();
    }
    else if ( strcmp($content, "시간표") == false ) {
        start_echo();
            start_msg();
                echo_text("몇 학년이야?", 0);
            end_msg(1);
            keyboard_button(array("1학년", "2학년", "3학년", "처음으로"));
        end_echo();
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
        start_echo();
            start_msg();
                echo_text("에러가 발생햇오요,,,끼야악", 0);
            end_msg(1);
            keyboard_button(array("급식", "날씨", "시간표", "게임 전적", "정보"));
        end_echo();
    }
?>
