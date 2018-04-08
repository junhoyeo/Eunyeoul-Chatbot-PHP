<?php
  // echo cool for Kakao PlusFriend Auto Reply API
  header("Content-type: application/json; charset=UTF-8");
  function start_echo(){
    echo '{ ';
    return;
  }
  function end_echo(){
    echo '}';
    return;
  }
  function start_msg(){
    echo '"message" : { ';
    return;
  }
  function end_msg($end){ // $end는 ','을 붙일지의 여부(1/0)
    if ($end==1){
      echo ' }, ';
      return;
    }
    echo ' } ';
    return;
  }
  function echo_text($text, $end){ // $text는 출력할 문자열, $end는 ','을 붙일지의 여부(1/0)
    $text = '"text" : "' . $text . '"';
    if ($end==1){
      echo $text . ', ';
      return;
    }
    echo $text;
    return;
  }
  function echo_photo($url, $width, $height, $end){ // $url은 출력할 사진 링크, $width/$height, $end는 ','을 붙일지의 여부(1/0)
    $photo = '"photo": { "url": "' . $url . '", "width": ' . $width . ', "height": ' . $height . ' }';
    if ($end==1){
      echo $photo . ', ';
      return;
    }
    echo $photo;
    return;
  }
  function echo_msgbutton($label, $url, $end){ // $label/$url, $end는 ','을 붙일지의 여부(1/0)
    $msgbutton = '"message_button": { "label": "' . $label . '", "url": "' . $url . '" }';
    if ($end==1){
      echo $msgbutton . ', ';
      return;
    }
    echo $msgbutton;
    return;
  }
  function keyboard_button($button_name){ // $button_name는 버튼 이름으로 구성된 배열
    $keyboard = '"keyboard" : { ';
    $type = '"type" : "buttons", ';
    $buttons = '"buttons" : [';
    for ($i = 0; $i < count($button_name); $i++) {
      $buttons = $buttons . '"' . $button_name[$i] . '"';
      if ($i !== count($button_name)-1){
        $buttons = $buttons . ', ';
      }
    }
    $buttons = $buttons . '] ';
    $keyboard = $keyboard . $type . $buttons . '} ';
    echo $keyboard;
    return;
  }
?>
