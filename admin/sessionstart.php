<?php
require("globaldefine.php");
session_start();
$langget= isset($_GET['lang']) ? $_GET['lang'] : '';
if (($langget=="english") || ($langget=="turkish"))
{
$_SESSION[_SESSIONPREFIX.'language']=$langget;
}
$userlang=(isset($_SESSION[_SESSIONPREFIX.'language'])) ? $_SESSION[_SESSIONPREFIX.'language']: 'english';
require ("lang_".$userlang.".php");
$filedir= dirname(__FILE__);
require "$filedir/themes/config_theme.php";
?>

