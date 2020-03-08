<?php

function form_line($f_name, $f_value, $f_size){
return "<INPUT type=\"text\" NAME=\"$f_name\" VALUE=\"$f_value\" SIZE=$f_size>";
}
function form_line_pass($f_name, $f_value, $f_size){
return "<INPUT type=\"password\" NAME=\"$f_name\" VALUE=\"$f_value\" SIZE=$f_size>";
}


function form_button($f_name, $f_value, $f_size){
return "<INPUT TYPE=\"submit\" NAME=\"$f_name\" VALUE=\"$f_value\" class=btn>";
}



$mtime = microtime();
$mtime = explode(" ",$mtime);
$mtime = $mtime[1] + $mtime[0];
$start_generate_time = $mtime;


function base64_unserialize($str){
$ary = unserialize($str);
if (is_array($ary)){
foreach ($ary as $k => $v){
if (is_array(unserialize($v))){
$ritorno[$k]=base64_unserialize($v);
}else{
$ritorno[$k]=base64_decode($v);
}
}
}else{
return false;
}
return $ritorno;
}
function base64_serialize($ary){
if (is_array($ary)){
foreach ($ary as $k => $v){
if (is_array($v)){
$ritorno[$k]=base64_serialize($v);
}else{
$ritorno[$k]=base64_encode($v);
}
}
}else{
return false;
}
return serialize ($ritorno);
}

function mbenchtime($start_time)
{
$mtime = microtime();
$mtime = explode(" ",$mtime);
$mtime = $mtime[1] + $mtime[0];
$end_time = $mtime;
$total_time=($end_time - $start_time);
return substr($total_time,0,5); 
}
?>
