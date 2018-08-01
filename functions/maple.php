<?php
function maplestory($username){
  header("Content-type: application/json; charset=UTF-8");
  require("Snoopy.class.php");
  $encoded_username = urlencode(iconv("UTF-8", "euc-kr", $username));
  $temp = "";
  for ($i=0; $i < strlen($encoded_username); $i++) {
    $temp = $temp . $encoded_username[$i];
  }
  $encoded_username = $temp;
  $URL = "http://maplestory.nexon.com/MapleStory/Page/GnxPopup.aspx?URL=MyMaple/POP_Profile&strCharacterName=";
  $URL = $URL . $encoded_username;
  $snoopy = new Snoopy; // snoopy 생성
  $snoopy->fetch($URL);

  //========== 캐릭터 이름 가져오기 ==========
  preg_match('/<h2 class="stt">(.*?)<\/h2>/is', $snoopy->results, $name);
  $name = $name[0];
  $name = str_replace("<h2 class=\"stt\">", "", $name);
  $name = str_replace("</h2>", "", $name);
  $name = iconv("euc-kr", "UTF-8", $name);

  //========== 아바타 이미지 주소 가져오기 ==========
  preg_match('/<div class="thm">(.*?)width/is', $snoopy->results, $avatar);
  $avatar = $avatar[0];
  $avatar = str_replace("<div class=\"thm\"><img src='", "", $avatar);
  $avatar = str_replace("' width", "", $avatar);

  preg_match('/<ul>(.*?)<\/ul>/is', $snoopy->results, $info);
  $info = $info[0];
  preg_match_all('/<span class="tx">(.*?)<\/span>/is', $info, $info);
  // info에 character_info가 저장된다.

  //========== 직업 가져오기 ==========
  $job = $info[0][0];
  $job = str_replace("<span class=\"tx\">", "", $job);
  $job = str_replace("</span>", "", $job);
  $job = iconv("euc-kr", "UTF-8", $job);

  //========== 레벨 가져오기 ==========
  $level = $info[0][1];
  $level = str_replace("<span class=\"tx\">", "", $level);
  $level = str_replace("</span>", "", $level);

  //========== 경험치 가져오기 ==========
  $exp = $info[0][2];
  $exp = str_replace("<span class=\"tx\">", "", $exp);
  $exp = str_replace("</span>", "", $exp);

  //========== 인기도 가져오기 ==========
  $pop = $info[0][3];
  $pop = str_replace("<span class=\"tx\">", "", $pop);
  $pop = str_replace("</span>", "", $pop);

  $final[0] = $avatar; // 이미지
  $final[1] = $name; // 이름
  $final[2] = $job; // 직업
  $final[3] = $level; // 레벨
  $final[4] = $exp; // 경험치
  $final[5] = $pop; // 인기도
  return $final;
}
function maple($username){
  $final = maplestory($username);
  $pic_url = $final[0];
  if ($final[1]=='') {
    echo json_encode(
        array(
            'message' => array(
                'text' => '검색결과가 없습니다.'
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
  else {
    $result = "캐릭터 이름 : " . $final[1] . "\\n" .
    "직업 : " . $final[2] . "\\n" .
    "레벨 : " . $final[3] . "\\n" .
    "경험치 : " . $final[4] . "\\n" .
    "인기도 : " . $final[5];
    echo json_encode(
        array(
            'message' => array(
                'text' => $result,
                'photo' => array(
                    'url' => $pic_url,
                    'width' => 600,
                    'height' => 600
                )
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
}
?>
