<?php
    include("meal.php");
    $data = json_decode(file_get_contents('php://input'), true);
    $content = $data["content"];

    if ( strpos($content, "대화 시작") !== false ) {
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
                "buttons" : ["급식", "정보"]
              }
            }';
    }
    else if ( strpos($content, "오늘 급식") !== false ) {
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
    else if ( strpos($content, "내일 급식") !== false ) {
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
    else if ( strpos($content, "내일 모레 급식") !== false ) {
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
    else if ( strpos($content, "급식") !== false ) {
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
    else if ( strpos($content, "정보") !== false ) {
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
    else if ( strpos($content, "기부하기") !== false ) {
        echo '{
              "message" :
              {
                "text" : "다양한 방법으로 나를 후원할 수 있오!!\\n계좌이체 : 국민은행 818702-00-018145 여준호\\n비트코인 : 1HnC2Y4tbNgcoErCcoZcmsnRzqcT5rdWon\\n이더리움 : 0x07B8CedbE8Ab83F06DFAdC39991910A4544dE3A1\\n비트코인 캐시 : qzuqmmmdxw5l00fjf7nzl7ur3jv2yr9vfv7f62trc0\\n다른 거래 수단을 원한다면 개발자에게 따로 말해주면 도와줄 수 있을 거야!"
              },
              "keyboard" :
              {
                "type" : "buttons",
                "buttons" : ["급식", "정보"]
              }
        }';
    }
/*
Bitcoin : 1HnC2Y4tbNgcoErCcoZcmsnRzqcT5rdWon
Ether : 0x07B8CedbE8Ab83F06DFAdC39991910A4544dE3A1
Bitcoin Cash : qzuqmmmdxw5l00fjf7nzl7ur3jv2yr9vfv7f62trc0
*/
    else if ( strpos($content, "처음으로") !== false ) {
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
    else{
        echo '{
              "message" :
              {
                "text" : "에러가 발생햇오요,,,끼야악"
              },
              "keyboard" :
              {
                "type" : "buttons",
                "buttons" : ["급식", "정보", "처음으로"]
              }
        }';
    }
?>
