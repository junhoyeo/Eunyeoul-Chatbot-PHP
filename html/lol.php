<?php
function lol_record($username){
  header("Content-type: application/json; charset=UTF-8");
  require("Snoopy.class.php");
  $URL = "http://www.op.gg/summoner/userName=" . urlencode($username);
  $snoopy = new Snoopy; // snoopy 생성
  $snoopy->fetch($URL);
  //========== 소환사의 레벨을 파싱 ==========
  preg_match('/<span class="Level tip" title="Level">(.*?)<\/span>/is', $snoopy->results, $level);
  $level = $level[0];
  $level = str_replace('<span class="Level tip" title="Level">', '', $level);
  $level = str_replace('</span>', '', $level);

  //========== 소환사의 티어를 파싱 ==========
  preg_match('/<span class="tierRank">(.*?)<\/span>/is', $snoopy->results, $rank);
  $rank = $rank[0];
  $rank = str_replace('<span class="tierRank">', '', $rank);
  $rank = str_replace('</span>', '', $rank);

  //========== LP 파싱 ==========
  preg_match('/<span class="LeaguePoints">(.*?)<\/span>/is', $snoopy->results, $lp);
  $lp = $lp[0];
  $lp = str_replace('<span class="LeaguePoints">', '', $lp);
  $lp = str_replace('</span>', '', $lp);
  $lp = preg_replace('/\s+/', '', $lp);
  $lp = preg_replace('/[A-Z]/', '', $lp);
  $lp = $lp . " LP";

  //========== 리그 파싱 ==========
  preg_match('/<div class="LeagueName">(.*?)<\/div>/is', $snoopy->results, $league);
  $league = $league[0];
  $league = str_replace('<div class="LeagueName">', '', $league);
  $league = str_replace('</div>', '', $league);
  $league = preg_replace('/\s+/', '', $league);
  $league = html_entity_decode($league, ENT_QUOTES);
  for ($i=0; $i < strlen($league); $i++) {
    if (ctype_upper($league[$i])){
      $temp = substr_replace($league, " ", $i, 0);
    }
  }
  $league = $temp;

  //========== 랭킹(순위) 파싱 ==========<span class="ranking">
  preg_match('/<span class="ranking">(.*?)<\/span>/is', $snoopy->results, $ranking);
  $ranking = $ranking[0];
  $ranking = str_replace('<span class="ranking">', '', $ranking);
  $ranking = str_replace('</span>', '', $ranking);
  $ranking = $ranking . "위";

  //========== 전체 승률 파싱 ==========
  //<span class="wins">382승</span>
	//<span class="losses">324패</span>
  preg_match('/<span class="wins">(.*?)<\/span>/is', $snoopy->results, $wins);
  $wins = $wins[0];
  $wins = str_replace('<span class="wins">', '', $wins);
  $wins = str_replace('</span>', '', $wins);
  $wins = str_replace('W', '승', $wins);
  preg_match('/<span class="losses">(.*?)<\/span>/is', $snoopy->results, $losses);
  $losses = $losses[0];
  $losses = str_replace('<span class="losses">', '', $losses);
  $losses = str_replace('</span>', '', $losses);
  $losses = str_replace('L', '패', $losses);
  $final = $wins . " " . $losses;

  //========== 소환사의 평균 킬, 뎃, 어시를 파싱 ==========
  preg_match('/<td class="KDA">(.*?)<\/td>/is', $snoopy->results, $kda);
  $kda = $kda[0];
  preg_match('/<span class="Kill">(.*?)<\/span>/is', $kda, $kill);
  preg_match('/<span class="Death">(.*?)<\/span>/is', $kda, $death);
  preg_match('/<span class="Assist">(.*?)<\/span>/is', $kda, $assist);
  $kill = $kill[0];
  $death = $death[0];
  $assist = $assist[0];
  $list_filter = array('<span class="Kill">', '<span class="Death">', '<span class="Assist">', '</span>');
  foreach ($list_filter as $filter) { // 필터링
      $kill = str_replace($filter, '', $kill);
      $death = str_replace($filter, '', $death);
      $assist = str_replace($filter, '', $assist);
  }

  //========== 소환사의 K/D를 파싱 ==========
  preg_match('/<span class="KDARatio">(.*?)<\/span>/is', $snoopy->results, $kd);
  $kd = $kd [0];
  $kd = str_replace('<span class="KDARatio">', '', $kd);
  $kd = str_replace('</span>', '', $kd);

  //========== 소환사의 승률을 파싱 ==========
  preg_match('/<td class="Summary">(.*?)<\/td>/is', $snoopy->results, $winning_rate);
  $winning_rate = $winning_rate[0];
  preg_match('/<div class="Text">(.*?)<\/div>/is', $winning_rate, $winning_rate);
  $winning_rate = $winning_rate[0];
  $winning_rate = str_replace('<div class="Text">', '', $winning_rate);
  $winning_rate = str_replace('</div>', '', $winning_rate);

  //========== 소환사의 킬관여율을 파싱 ==========
  preg_match('/<td class="KDA">(.*?)<\/td>/is', $snoopy->results, $kda);
  $kda = $kda[0];
  preg_match('/<span>(.*?)<\/span>/is', $kda, $kill_involvement);
  $kill_involvement = $kill_involvement[0];
  $kill_involvement = str_replace('<span>', '', $kill_involvement);
  $kill_involvement = str_replace('</span>', '', $kill_involvement);

  //========== 소환사의 선호 포지션을 파싱 ==========
  preg_match('/<td class="KDA">(.*?)<\/td>/is', $snoopy->results, $kda);
  $kda = $kda[0];
  preg_match('/<span>(.*?)<\/span>/is', $kda, $kill_involvement);
  $kill_involvement = $kill_involvement[0];
  $kill_involvement = str_replace('<span>', '', $kill_involvement);
  $kill_involvement = str_replace('</span>', '', $kill_involvement);

  $record = "소환사명 : " . $username . "\\n";
  $record = $record . "레벨 : " . $level . "\\n\\n";
  if($level == ''){ //정보 없음
      $record = "소환사 정보가 없습니다.\\n닉네임을 확인하세요.";
      $return[0] = $record;
      $return[1] = '';
      return $return;
  }
  $record = $record . "티어 : " . $rank . "\\n";
  if ($rank !== 'Unranked'){ // 티어가 Unranked가 아닐 경우
    $record = $record . "LP : " . $lp . "\\n";
    $record = $record . "리그 : " . $league . "\\n";
    $record = $record . "리그 랭킹 : " . $ranking . "\\n";
    $record = $record . "리그 전적 : " . $final . "\\n";
  }
  $record = $record . "\\nK/D : " . $kd . "\\n";
  $record = $record . "평균 Kill : " . $kill . "\\n";
  $record = $record . "평균 Death : " . $death . "\\n";
  $record = $record . "평균 Assist : " . $assist . "\\n";
  $record = $record . "승률 : " . $winning_rate . "\\n";
  $record = $record . "킬관여율 : " . $kill_involvement;
  $return[0] = $record; // 전적 정보
  $return[1] = $URL; // OP.GG 링크
  $return[2] = $rank; // 티어 
  return $return;
}
 ?>
