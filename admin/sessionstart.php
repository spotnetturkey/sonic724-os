<?php
require("globaldefine.php");
session_start();
if (($_GET[lang]=="english") || ($_GET[lang]=="turkish"))
{
$_SESSION[_SESSIONPREFIX.'language']=$_GET[lang];
}
$userlang=(isset($_SESSION[_SESSIONPREFIX.'language'])) ? $_SESSION[_SESSIONPREFIX.'language']: 'english';
require ("lang_".$userlang.".php");
define('_PANELVERSION','0.1a');
$filedir= dirname(__FILE__);
require "$filedir/themes/config_theme.php";
?>

