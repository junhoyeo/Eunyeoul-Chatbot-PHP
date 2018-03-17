<?php
function getweather()
{
  // http://www.weather.go.kr/wid/queryDFSRSS.jsp?zone=4157057000
  // 경기도 김포시 구래동 기준 기상 데이터(현재 시간으로 봐서 가장 최근 데이터를 가져옴)
  // 데이터는 3시간마다 갱신되고 가장 최근 데이터는 인덱스가 0인 것으로 추정
  // 이후 인덱스는 예보인 듯
  header("Content-type: application/json; charset=UTF-8");        // json type and UTF-8 encoding
  require("Snoopy.class.php");
  $URL = "http://www.weather.go.kr/wid/queryDFSRSS.jsp?zone=4157057000"; // DOMDocument
  // url 생성
  $snoopy = new Snoopy; // snoopy 생성
  $snoopy->fetch($URL);

  preg_match('/<body>(.*?)<\/body>/is', $snoopy->results, $body); // body 추출
  $weather_data = $body[0];
  $date = 0; // 가장 최근에 발표된 데이터

  $re = "/<data seq=\"" . $date . "\">(.*?)<\/data>/is";
  preg_match($re, $weather_data, $weather_data); // 가장 최근 시간의 데이터를 추출
  $weather_data = $weather_data[0]; // 최근 시간의 데이터는 여기에 저장되어 있음

  //echo $re; //디버깅에 사용
  //echo $weather_data; // 디버깅에 사용

  preg_match('/<temp>(.*?)<\/temp>/is', $weather_data, $temp); // 현재 시간 온도 => $temp
  $temp = $temp[0];
  preg_match('/<tmx>(.*?)<\/tmx>/is', $weather_data, $tmx); // 오늘 최고 온도 => $tmx
  $tmx = $tmx[0];
  preg_match('/<tmn>(.*?)<\/tmn>/is', $weather_data, $tmn); // 오늘 최저 온도 => $tmn
  $tmn = $tmn[0];

  preg_match('/<sky>(.*?)<\/sky>/is', $weather_data, $sky); // 하늘 상태 코드 => $sky
  $sky = $sky[0];
  /* 하늘 상태 코드
  ① 1 : 맑음
  ② 2 : 구름조금
  ③ 3 : 구름많음
  ④ 4 : 흐림
  */
  $sky = str_replace(1, '맑음', $sky);
  $sky = str_replace(2, '구름 조금', $sky);
  $sky = str_replace(3, '구름 많음', $sky);
  $sky = str_replace(4, '흐림', $sky);

  preg_match('/<pty>(.*?)<\/pty>/is', $weather_data, $pty); // 강수 상태 코드 => $pty
  $pty = $pty[0];
  /* 강수 상태 코드
  ① 0 : 없음
  ② 1 : 비
  ③ 2 : 비/눈
  ④ 3 : 눈/비
  ⑤ 4 : 눈
  */
  $pty = str_replace(0, '없음', $pty);
  $pty = str_replace(1, '비', $pty);
  $pty = str_replace(2, '비/눈', $pty);
  $pty = str_replace(3, '눈/비', $pty);
  $pty = str_replace(4, '눈', $pty);

  preg_match('/<wfKor>(.*?)<\/wfKor>/is', $weather_data, $wfKor); // 날씨 => $wfKor
  $wfKor = $wfKor[0];
  preg_match('/<pop>(.*?)<\/pop>/is', $weather_data, $pop); // 강수 확률 => $pop
  $pop = $pop[0];
  preg_match('/<reh>(.*?)<\/reh>/is', $weather_data, $reh); // 습도 => $reh
  $reh = $reh[0];

  $list_filter = array('<temp>', '</temp>', '<tmx>', '</tmx>', '<tmn>', '</tmn>',
  '<sky>', '</sky>', '<pty>', '</pty>', '<wfKor>', '</wfKor>',
  '<pop>', '</pop>', '<reh>', '</reh>');
  foreach ($list_filter as $filter) { // 필터링
      $temp = str_replace($filter, '', $temp);
      $tmx = str_replace($filter, '', $tmx);
      $tmn = str_replace($filter, '', $tmn);
      $sky = str_replace($filter, '', $sky);
      $pty = str_replace($filter, '', $pty);
      $wfKor = str_replace($filter, '', $wfKor);
      $pop = str_replace($filter, '', $pop);
      $reh = str_replace($filter, '', $reh);
  }
  if ($tmx == -999.0){
    $tmx = "데이터 없음";
  }
  if ($tmn == -999.0){
    $tmn = "데이터 없음";
  }
  $return[0] = "현재 시간 온도 : " . $temp . "\\n";
  $return[1] = "최고 온도 : " . $tmx . "\\n";
  $return[2] = "최저 온도 : " . $tmn . "\\n";
  $return[3] = "하늘 상태 : " . $sky . "\\n";
  $return[4] = "강수 상태 : " . $pty . "\\n";
  $return[5] = "날씨 : " . $wfKor . "\\n";
  $return[6] = "강수 확률 : " . $pop . "%\\n";
  $return[7] = "습도 : " . $reh . "%";
  $return[8] = $wfKor; // 그림 출력을 위해서 날씨 값도
  return $return;
}
/*
$array = getweather();
for ($i=0; $i < 8; $i++) {
  echo $array[$i];
}*/
// usage는 위와 같음
?>
