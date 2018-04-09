<?php
    include("functions/meal.php");
    include("functions/weather.php");
    include("functions/lol.php");
    include("functions/pubg.php");
    include("functions/maple.php");
    include("functions/timetable.php");
    include("functions/echoKakao.php");
    $data = json_decode(file_get_contents('php://input'), true);
    $content = $data["content"];

    if ( strcmp($content, "대화 시작") == false ) {
        start_echo();
            start_msg();
                echo_text("안녕! 나는 은여울중학교 급식봇이야! ><", 1);
                echo_photo("http://silvermealbot.dothome.co.kr/images/logo.jpg", 600, 600, 0);
            end_msg(1);
            keyboard_button(array("급식", "날씨", "시간표", "게임 전적", "정보"));
        end_echo();
    }
    else if ( strcmp($content, "오늘 급식") == false ) {
        $final = getmeal(0);
        $logfile = fopen("log.txt", 'a') or die();
        fwrite($logfile, $_SERVER['REMOTE_ADDR'] . " / " . date("Y.m.d H:i:s",time()) . " 오늘 급식을 조회했습니다.\n");
        // 아이피, 검색 시간과 조회 내용이 기록됨
        fclose($logfile);
        start_echo();
            start_msg();
                echo_text($final[0] . "\\n은여울중학교 급식 정보야!\\n\\n" . $final[1], 0);
            end_msg(1);
            keyboard_button(array("오늘 급식", "내일 급식", "내일 모레 급식", "처음으로"));
        end_echo();
    }
    else if ( strcmp($content, "내일 급식") == false ) {
        $final = getmeal(1);
        $logfile = fopen("log.txt", 'a') or die();
        fwrite($logfile, $_SERVER['REMOTE_ADDR'] . " / " . date("Y.m.d H:i:s",time()) . " 내일 급식을 조회했습니다.\n");
        // 아이피, 검색 시간과 조회 내용이 기록됨
        fclose($logfile);
        start_echo();
            start_msg();
                echo_text($final[0] . "\\n은여울중학교 급식 정보야!\\n\\n" . $final[1], 0);
            end_msg(1);
            keyboard_button(array("오늘 급식", "내일 급식", "내일 모레 급식", "처음으로"));
        end_echo();
    }
    else if ( strcmp($content, "내일 모레 급식") == false ) {
        $final = getmeal(2);
        $logfile = fopen("log.txt", 'a') or die();
        fwrite($logfile, $_SERVER['REMOTE_ADDR'] . " / " . date("Y.m.d H:i:s",time()) . " 내일 모레 급식을 조회했습니다.\n");
        // 아이피, 검색 시간과 조회 내용이 기록됨
        fclose($logfile);
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
        $logfile = fopen("log.txt", 'a') or die();
        fwrite($logfile, $_SERVER['REMOTE_ADDR'] . " / " . date("Y.m.d H:i:s",time()) . " 날씨를 조회했습니다.\n");
        // 아이피, 검색 시간과 조회 내용이 기록됨
        fclose($logfile);
        $final = getweather();
        $weather = $final[8];
        $final = $final[0] . $final[1] . $final[2] . $final[3] . $final[4] . $final[5] . $final[6] . $final[7];
        $final = "경기도 김포시 구래동 기준 날씨야~!\\n" . $final;
        //  날씨
        // ① 맑음 - sunny.jpg
        // ② 구름 조금 - cloudy.jpg
        // ③ 구름 많음 - cloudy.jpg
        // ④ 흐림 - mist.jpg
        // ⑤ 비 - rain.jpg
        // ⑥ 눈/비 - rain.jpg
        // ⑦ 눈 - snow.jpg
        $pic_url = "http:\/\/silvermealbot.dothome.co.kr\/images\/";
        if (strcmp($weather, "맑음") == false){
            $pic_url = $pic_url . "sunny.jpg";
        }
        else if ( strpos($weather, "구름") !== false ){
            $pic_url = $pic_url . "cloudy.jpg";
        }
        else if (strcmp($weather, "흐림") == false){
            $pic_url = $pic_url . "mist.jpg";
        }
        else if ( strpos($weather, "비") !== false ){
            $pic_url = $pic_url . "rain.jpg";
        }
        else if (strcmp($weather, "눈") == false){
            $pic_url = $pic_url . "snow.jpg";
        }
        start_echo();
            start_msg();
                echo_text($final, 1);
                echo_photo($pic_url, 600, 600, 0);
            end_msg(1);
            keyboard_button(array("급식", "날씨", "시간표", "게임 전적", "정보"));
        end_echo();
    }
    else if ( strcmp($content, "정보") == false ) {
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
        start_echo();
            start_msg();
                echo_text("'롤'과 소환사명을 함께 입력해줘~!\\n".
                "예시 : '롤 은여울중학교'", 0);
            end_msg(0);
        end_echo();
    }
    else if ( strcmp($content, "PUBG") == false ) {
        start_echo();
            start_msg();
                echo_text("배그 전적은 아직 개발중이야 제발 아무것도 누르지마\\n".
                "'백'과 배그닉넴을 함께 입력해줘~!\\n".
                "예시 : '백 은여울중학교'", 0);
            end_msg(0);
        end_echo();
    }
    else if ( strcmp($content, "Maplestory") == false ) {
        start_echo();
            start_msg();
                echo_text("'멮'과 캐릭터 이름을 함께 입력해줘~!\\n".
                "예시 : '멮 은여울중학교'", 0);
            end_msg(0);
        end_echo();
    }
    else if ( strpos($content, "롤") !== false ) {
          $username = str_replace('롤 ', '', $content);
          $return = lol_record($username);
          $logfile = fopen("log.txt", 'a') or die();
          fwrite($logfile, $_SERVER['REMOTE_ADDR'] . " / " . date("Y.m.d H:i:s",time()) . " '" . $username . "' 소환사를 검색했습니다(롤).\n");
          // 아이피, 검색 시간과 기록이 로그 파일에 기록됨
          fclose($logfile);
          $record = $return[0];
          $last = $return[1];
          $tier = $return[2];
          if ($last == ''){ // 유효한 소환사명이 아님 => message_button 표시 X
              start_echo();
                  start_msg();
                      echo_text("$record", 0);
                  end_msg(1);
                  keyboard_button(array("League of Legends", "PUBG", "Maplestory", "처음으로"));
              end_echo();
          }
          else{ // 유효한 소환사명 => message_button 표시 O
              $pic_url = "http://silvermealbot.dothome.co.kr/images/tier/";
              $tier = strtolower($tier);
              $tier = str_replace(' ', '_', $tier);
              $pic_url = $pic_url . $tier . ".png";
              start_echo();
                  start_msg();
                      echo_text($record, 1);
                      echo_photo($pic_url, 600, 600, 1);
                      echo_msgbutton("OP.GG에서 정보 확인", $last, 0);
                  end_msg(1);
                  keyboard_button(array("League of Legends", "PUBG", "Maplestory", "처음으로"));
              end_echo();
          }
    }
    else if ( strpos($content, "백") !== false ) {
        $username = str_replace('백 ', '', $content);
        $logfile = fopen("log.txt", 'a') or die();
        fwrite($logfile, $_SERVER['REMOTE_ADDR'] . " / " . date("Y.m.d H:i:s",time()) . " '" . $username . "' 닉네임을 검색했습니다(배그).\n");
        // 아이피, 검색 시간과 기록이 로그 파일에 기록됨
        fclose($logfile);
        start_echo();
            start_msg();
                echo_text("개발중이야 제발 아무것도 누르지마", 0);
            end_msg(1);
            keyboard_button(array("League of Legends", "PUBG", "Maplestory", "처음으로"));
        end_echo();
    }
    else if ( strpos($content, "멮") !== false ) {
      $username = str_replace('멮 ', '', $content);
      $final = maplestory($username);
      $pic_url = $final[0];
      $logfile = fopen("log.txt", 'a') or die();
      fwrite($logfile, $_SERVER['REMOTE_ADDR'] . " / " . date("Y.m.d H:i:s",time()) . " '" . $username . "' 캐릭터를 검색했습니다(메플).\n");
      // 아이피, 검색 시간과 기록이 로그 파일에 기록됨
      fclose($logfile);
      if ($final[1]=='') {
        start_echo();
            start_msg();
                echo_text("검색결과가 없습니다.", 0);
            end_msg(1);
            keyboard_button(array("League of Legends", "PUBG", "Maplestory", "처음으로"));
        end_echo();
      }
      else {
          $result = "캐릭터 이름 : " . $final[1] . "\\n" .
          "직업 : " . $final[2] . "\\n" .
          "레벨 : " . $final[3] . "\\n" .
          "경험치 : " . $final[4] . "\\n" .
          "인기도 : " . $final[5];
          start_echo();
              start_msg();
                  echo_text($result, 1);
                  echo_photo($pic_url, 600, 600, 0);
              end_msg(1);
              keyboard_button(array("League of Legends", "PUBG", "Maplestory", "처음으로"));
          end_echo();
      }
    }
    else if ( strcmp($content, "시간표") == false ) {
        start_echo();
            start_msg();
                echo_text("언제 시간표가 필요해?", 0);
            end_msg(1);
            keyboard_button(array("오늘 시간표", "내일 시간표", "내일 모레 시간표", "처음으로"));
        end_echo();
    }
    else if ( strcmp($content, "오늘 시간표") == false ) {
        start_echo();
            start_msg();
                echo_text("시간표를 조회할 학급을 선택해줘^^7", 0);
            end_msg(1);
            keyboard_button(array("3-1 (오늘)", "3-3 (오늘)", "3-5 (오늘)", "처음으로"));
        end_echo();
    }
    else if ( strcmp($content, "내일 시간표") == false ) {
        start_echo();
            start_msg();
                echo_text("시간표를 조회할 학급을 선택해줘^^7", 0);
            end_msg(1);
            keyboard_button(array("3-1 (내일)", "3-3 (내일)", "3-5 (내일)", "처음으로"));
        end_echo();
    }
    else if ( strcmp($content, "내일 모레 시간표") == false ) {
        start_echo();
            start_msg();
                echo_text("시간표를 조회할 학급을 선택해줘^^7", 0);
            end_msg(1);
            keyboard_button(array("3-1 (내일 모레)", "3-3 (내일 모레)", "3-5 (내일 모레)", "처음으로"));
        end_echo();
    }
    else if ( strcmp($content, "3-1 (오늘)") == false ) {
        $table_today = get_timetable_class(1, date('w'));
        start_echo();
            start_msg();
                echo_text("$table_today", 0);
            end_msg(1);
            keyboard_button(array("오늘 시간표", "내일 시간표", "내일 모레 시간표", "처음으로"));
        end_echo();
    }
    else if ( strcmp($content, "3-3 (오늘)") == false ) {
        $table_today = get_timetable_class(3, date('w'));
        start_echo();
            start_msg();
                echo_text("$table_today", 0);
            end_msg(1);
            keyboard_button(array("오늘 시간표", "내일 시간표", "내일 모레 시간표", "처음으로"));
        end_echo();
    }
    else if ( strcmp($content, "3-5 (오늘)") == false ) {
        $table_today = get_timetable_class(5, date('w'));
        start_echo();
            start_msg();
                echo_text("$table_today", 0);
            end_msg(1);
            keyboard_button(array("오늘 시간표", "내일 시간표", "내일 모레 시간표", "처음으로"));
        end_echo();
    }
    else if ( strcmp($content, "3-1 (내일)") == false ) {
        $day = date('w')+1;
        if ($day > 6){
          $day -= 6;
        }
        $table_today = get_timetable_class(1, $day);
        start_echo();
            start_msg();
                echo_text("$table_today", 0);
            end_msg(1);
            keyboard_button(array("오늘 시간표", "내일 시간표", "내일 모레 시간표", "처음으로"));
        end_echo();
    }
    else if ( strcmp($content, "3-3 (내일)") == false ) {
        $day = date('w')+1;
        if ($day > 6){
          $day -= 6;
        }
        $table_today = get_timetable_class(3, $day);
        start_echo();
            start_msg();
                echo_text("$table_today", 0);
            end_msg(1);
            keyboard_button(array("오늘 시간표", "내일 시간표", "내일 모레 시간표", "처음으로"));
        end_echo();
    }
    else if ( strcmp($content, "3-5 (내일)") == false ) {
        $day = date('w')+1;
        if ($day > 6){
          $day -= 6;
        }
        $table_today = get_timetable_class(5, $day);
        start_echo();
            start_msg();
                echo_text("$table_today", 0);
            end_msg(1);
            keyboard_button(array("오늘 시간표", "내일 시간표", "내일 모레 시간표", "처음으로"));
        end_echo();
    }
    else if ( strcmp($content, "3-1 (내일 모레)") == false ) {
        $day = date('w')+2;
        if ($day > 6){
          $day -= 6;
        }
        $table_today = get_timetable_class(1, $day);
        start_echo();
            start_msg();
                echo_text("$table_today", 0);
            end_msg(1);
            keyboard_button(array("오늘 시간표", "내일 시간표", "내일 모레 시간표", "처음으로"));
        end_echo();
    }
    else if ( strcmp($content, "3-3 (내일 모레)") == false ) {
        $day = date('w')+2;
        if ($day > 6){
          $day -= 6;
        }
        $table_today = get_timetable_class(3, $day);
        start_echo();
            start_msg();
                echo_text("$table_today", 0);
            end_msg(1);
            keyboard_button(array("오늘 시간표", "내일 시간표", "내일 모레 시간표", "처음으로"));
        end_echo();
    }
    else if ( strcmp($content, "3-5 (내일 모레)") == false ) {
        $day = date('w')+2;
        if ($day > 6){
          $day -= 6;
        }
        $table_today = get_timetable_class(5, $day);
        start_echo();
            start_msg();
                echo_text("$table_today", 0);
            end_msg(1);
            keyboard_button(array("오늘 시간표", "내일 시간표", "내일 모레 시간표", "처음으로"));
        end_echo();
    }
    else{
        $logfile = fopen("log.txt", 'a') or die();
        fwrite($logfile, $_SERVER['REMOTE_ADDR'] . " / " . date("Y.m.d H:i:s",time()) . " '" . $content . "'(이)라고 입력하여 에러가 발생했습니다.\n");
        // 아이피, 검색 시간과 기록이 로그 파일에 기록됨
        fclose($logfile);
        start_echo();
            start_msg();
                echo_text("에러가 발생햇오요,,,끼야악", 0);
            end_msg(1);
            keyboard_button(array("급식", "날씨", "시간표", "게임 전적", "정보"));
        end_echo();
    }
?>
