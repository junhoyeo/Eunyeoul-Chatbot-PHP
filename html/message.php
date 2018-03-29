<?php
    include("meal.php");
    include("weather.php");
    include("lol.php");
    include("pubg.php");
    $data = json_decode(file_get_contents('php://input'), true);
    $content = $data["content"];

    if ( strcmp($content, "대화 시작") == false ) {
        echo '{
              "message" :
              {
                "text" : "안녕! 나는 은여울중학교 급식봇이야! ><",
                "photo": {
                    "url": "http://silvermealbot.dothome.co.kr/images/logo.jpg",
                    "width": 600,
                    "height": 600
                }
              },
              "keyboard" :
              {
                "type" : "buttons",
                "buttons" : ["급식", "날씨", "시간표", "게임 전적", "정보"]
              }
            }';
    }
    else if ( strcmp($content, "오늘 급식") == false ) {
        $final = getmeal(0);
echo <<< EOD
    {
        "message": {
            "text": "$final[0]\\n은여울중학교 급식 정보야!\\n\\n$final[1]"
        },
        "keyboard" :
        {
          "type" : "buttons",
          "buttons" : ["오늘 급식", "내일 급식", "내일 모레 급식", "처음으로"]
        }
    }
EOD;
    }
    else if ( strcmp($content, "내일 급식") == false ) {
      $final = getmeal(1);
echo <<< EOD
  {
      "message": {
          "text": "$final[0]\\n은여울중학교 급식이야!\\n\\n$final[1]"
      },
      "keyboard" :
      {
        "type" : "buttons",
        "buttons" : ["오늘 급식", "내일 급식", "내일 모레 급식", "처음으로"]
      }
  }
EOD;
    }
    else if ( strcmp($content, "내일 모레 급식") == false ) {
      $final = getmeal(2);
echo <<< EOD
  {
      "message": {
          "text": "$final[0]\\n은여울중학교 급식이야!\\n\\n$final[1]"
      },
      "keyboard" :
      {
        "type" : "buttons",
        "buttons" : ["오늘 급식", "내일 급식", "내일 모레 급식", "처음으로"]
      }
  }
EOD;
    }
    else if ( strcmp($content, "급식") == false ) {
        echo '{
              "message" :
              {
                "text" : "언제 급식을 알고 싶어?"
              },
              "keyboard" :
              {
                "type" : "buttons",
                "buttons" : ["오늘 급식", "내일 급식", "내일 모레 급식", "처음으로"]
              }
            }';
    }
    else if ( strcmp($content, "날씨") == false ) {
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
echo <<< EOD
  {
      "message": {
          "text": "$final",
          "photo": {
              "url": "$pic_url",
              "width": 600,
              "height": 600
          }
      },
      "keyboard" :
      {
        "type" : "buttons",
        "buttons" : ["급식", "날씨", "시간표", "게임 전적", "정보"]
      }
  }
EOD;
}
    else if ( strcmp($content, "정보") == false ) {
        echo '{
              "message" :
              {
                "text" : "아까도 말했듯이 나는 은여울중학교의 급식봇이야!\\n급식 데이터를 교육청 페이지에서 파싱해와서 알려주는거지.\\n내가 더 성장할 수 있도록 개발자 기부를 통해서 후원해주면 고맙겠엉!!",
                "photo": {
                    "url": "http://silvermealbot.dothome.co.kr/images/gibu.jpg",
                    "width": 600,
                    "height": 650
                }
              },
              "keyboard" :
              {
                "type" : "buttons",
                "buttons" : ["기부하기", "처음으로"]
              }
            }';
    }
    else if ( strcmp($content, "기부하기") == false ) {
        echo '{
              "message" :
              {
                "text" : "다양한 방법으로 나를 후원할 수 있어!!\\n계좌이체 : 국민은행 818702-00-018145 여준호\\n비트코인 : 1HnC2Y4tbNgcoErCcoZcmsnRzqcT5rdWon\\n이더리움 : 0x07B8CedbE8Ab83F06DFAdC39991910A4544dE3A1\\n비트코인 캐시 : qzuqmmmdxw5l00fjf7nzl7ur3jv2yr9vfv7f62trc0\\n다른 거래 수단을 원한다면 개발자에게 따로 말해주면 도와줄 수 있을 거야!"
              },
              "keyboard" :
              {
                "type" : "buttons",
                "buttons" : ["급식", "날씨", "시간표", "게임 전적", "정보"]
              }
        }';
    }
/*
Bitcoin : 1HnC2Y4tbNgcoErCcoZcmsnRzqcT5rdWon
Ether : 0x07B8CedbE8Ab83F06DFAdC39991910A4544dE3A1
Bitcoin Cash : qzuqmmmdxw5l00fjf7nzl7ur3jv2yr9vfv7f62trc0
*/
    else if ( strcmp($content, "처음으로") == false ) {
        echo '{
              "message" :
              {
                "text" : "처음으로 돌아왔습니다."
              },
              "keyboard" :
              {
                "type" : "buttons",
                "buttons" : ["대화 시작"]
              }
        }';
    }
    else if ( strcmp($content, "게임 전적") == false ) {
        echo '{
              "message" :
              {
                "text" : "게임 전적을 확인할 게임을 선택해줘~\\n참고로 검색기록은 에러 발생 시 보다 빠른 대응 및 보안 문제 방지를 위해서 로그에 기록되니 이해해줘!\\n걱정 마. 누가 검색했는지는 알 수가 없거든^^7"
              },
              "keyboard" :
              {
                "type" : "buttons",
                "buttons" : ["League of Legends", "PUBG", "처음으로"]
              }
        }';
    }
    else if ( strcmp($content, "League of Legends") == false ) {
        echo '{
              "message" :
              {
                "text" : "\'롤\'과 소환사명을 함께 입력해줘~!\\n예시 : \'롤 은여울중학교\'"
              }
        }';
    }
    else if ( strcmp($content, "PUBG") == false ) {
        echo '{
              "message" :
              {
                "text" : "배그 전적은 아직 개발중이야 제발 아무것도 누르지마\\n\'백\'과 배그닉넴을 함께 입력해줘~!\\n예시 : \'백 은여울중학교\'"
              }
        }';
    }
    else if ( strpos($content, "롤") !== false ) {
          $username = str_replace('롤 ', '', $content);
          $return = lol_record($username);
          $logfile = fopen("log.txt", 'a') or die();
          fwrite($logfile, date("Y.m.d H:i:s",time()) . " '" . $username . "' 소환사를 검색했습니다(롤).\n");
          // 검색 시간과 기록이 로그 파일에 기록됨
          fclose($logfile);
          $record = $return[0];
          $last = $return[1];
          $tier = $return[2];
          if ($last == ''){ // 유효한 소환사명이 아님 => message_button 표시 X
echo <<< EOD
    {
        "message" :
        {
            "text" : "$record"
        },
        "keyboard" :
        {
          "type" : "buttons",
          "buttons" : ["League of Legends", "PUBG", "처음으로"]
        }
    }
EOD;
          }
          else{ // 유효한 소환사명 => message_button 표시 O
            $pic_url = "http:\/\/silvermealbot.dothome.co.kr\/images\/\/tier\/";
            $tier = strtolower($tier);
            $tier = str_replace(' ', '_', $tier);
            $pic_url = $pic_url . $tier . ".png";
echo <<< EOD
    {
        "message" :
        {
            "text" : "$record",
            "photo" : {
                "url" : "$pic_url",
                "width" : 600,
                "height" : 600
            },
            "message_button": {
                "label": "OP.GG에서 정보 확인",
                "url": "$last"
            }
        },
        "keyboard" :
        {
          "type" : "buttons",
          "buttons" : ["League of Legends", "PUBG", "처음으로"]
        }
    }
EOD;
          }
/*echo <<< EOD
{
    "message" :
    {
        "text" : "$record\\n$last"
    },
    "keyboard" :
    {
      "type" : "buttons",
      "buttons" : ["League of Legends", "PUBG", "처음으로"]
    }
}
EOD;*/
}
    else if ( strpos($content, "백") !== false ) {
        $username = str_replace('백 ', '', $content);
echo <<< EOD
{
  "message" :
  {
      "text" : "개발중이야 제발 아무것도 누르지마"
  },
  "keyboard" :
  {
    "type" : "buttons",
    "buttons" : ["League of Legends", "PUBG", "처음으로"]
  }
}
EOD;
}
    else if ( strcmp($content, "시간표") == false ) {
      $table_today = "오늘 3학년 1반 기본 시간표야!\\n";
      $table[0] = "오늘은 수업이 없습니다."; // 일요일(0)
      $table[1] = "체육\\n도덕\\n도덕\\n영어\\n미술\\n미술"; // 월요일(1)
      $table[2] = "수학\\n과학\\n국어\\n사회\\n역사\\n역사"; // 화요일(2)
      $table[3] = "체육\\n기가\\n과학\\n국어\\n영어\\n진직"; // 수요일(3)
      $table[4] = "기가\\n기가\\n수학\\n과학\\n영어\\n국어"; // 목요일(4)
      $table[5] = "사회\\n영어\\n수학\\n국어\\n체육\\n과학"; // 금요일(5)
      $table[6] = "오늘은 수업이 없습니다."; // 토요일(6)
      $day = date('w');
      $table_today = $table_today . $table[$day];
echo <<< EOD
{
  "message" :
  {
      "text" : "$table_today"
  },
  "keyboard" :
  {
    "type" : "buttons",
    "buttons" : ["급식", "날씨", "시간표", "게임 전적", "정보"]
  }
}
EOD;
    }
    else{
        echo '{
              "message" :
              {
                "text" : "에러가 발생햇오요,,,끼야악"
              },
              "keyboard" :
              {
                "type" : "buttons",
                "buttons" : ["급식", "날씨", "시간표", "게임 전적", "정보"]
              }
        }';
    }
?>
