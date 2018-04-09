<?php
function get_timetable_class($class, $day){
  header("Content-type: application/json; charset=UTF-8");
  $logfile = fopen("log.txt", 'a') or die();
  fwrite($logfile, $_SERVER['REMOTE_ADDR'] . " / " . date("Y.m.d H:i:s",time()) . ' ' . $day . "일 후의 " . $class . "반 시간표를 조회했습니다.\n");
  // 아이피, 검색 시간과 조회 내용이 기록됨
  fclose($logfile);
  $table_today = "3학년 " . $class . "반 기본 시간표야!\\n";
  // 만약 요일이 일요일(0)이거나 토요일(6)이면 수업 없음을 출력하고 끝내면 되고
  // 아니라면 파일에서 해당 요일의 기본 시간표를 가져와야 함
  // n요일이라고 하면 n*6-6+1줄에서 n*6줄까지 읽어오면 됨
  if ( $day == 0 || $day == 6 ){ // 일요일이나 토요일
    $table_today = $table_today . "\\n수업이 없습니다.";
  }
  else { // 평일
    $count = 0;
    $handle = fopen("timetable/3-" . $class . ".txt", "r");
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
?>
