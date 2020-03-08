<?php
$userlang=(isset($_POST['language'])) ? $_POST['language']: 'turkish';

//$userlang='turkish';
require ("lang_".$userlang.".php");
require("globaldefine.php");
require ("config.php");

$theme='default';

$filedir= dirname(__FILE__);
require "./themes/config_theme.php";


if (admin_login($_POST['username'],$_POST['password'],$_POST['code'],$_POST['theme']))
{

if ($_SESSION[_SESSIONPREFIX.'homepage'])
{
header("Location:".$_SESSION[_SESSIONPREFIX.'homepage']);
die();
}
else
{
die();
}
} else 
{


$getmsg=(isset($_GET["msg"])) ? $_GET["msg"] :$message[0];
require("tpl_adminlogin.php");
} 


function admin_login($username,$password,$code,$theme)
{
global $PALA;
global $userlang;
if((!$username) || (!$password) ){
return false;
}

if($username!="admin"){
return false;
}

$password = md5($password);

$pass=trim(file_get_contents("/var/config/users"));
if ($password==$pass){
	session_start();
$_SESSION[_SESSIONPREFIX.'usertheme']=$theme;
$_SESSION[_SESSIONPREFIX.'username']="admin";
$_SESSION[_SESSIONPERMPREFIX.'auth']="yes";
$_SESSION[_SESSIONPREFIX.'homepage'] ="adv_time_edit.php?day=".date("w",time())."&h=".date("H:i",time()+120);	
$_SESSION[_SESSIONPREFIX.'language'] =$userlang;	
return true;
}
return false;
}

function option_list($optionlist,$name,$var)
{
$list= "<select name=\"$name\">";
foreach($optionlist as $key=>$option)
{
if ($key==$var)
{
$list.="<option value=\"$key\" selected>$option</option>\n";
} else
{
	$list.="<option value=\"$key\" >$option</option>\n";
}
}
$list.="</select>";
return $list;
}
?> 
