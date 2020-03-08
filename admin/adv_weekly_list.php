<?php
require ("sessionstart.php");
if ($_SESSION[_SESSIONPERMPREFIX.'auth'] != "yes")
{
header("Location:"._PERMERRORURL);
die();	
}	

require ("config.php");

$PAGETITLE=_WEEKLY_LIST;

include ("tpl_controlpanel.php");

echo "
<div class='ba'>
"._WEEKLY_LIST."</div><br>";
$day=1;
$list.="<table width=80% border=0 style=\"border:1px solid #cccccc\">";

$DAYS=array(_DAY0,_DAY1,_DAY2,_DAY3,_DAY4,_DAY5,_DAY6);

for ($w=1;$w<8;$w++)
{
if ($w==7) {
$mday=0;} else $mday=$w;

$week=$DAYS[$mday];	
$list.="<tr><td style='border-bottom:1px dotted #cccccc'><span style=\" font-size:16px;font-weight:bold;\">$week</span></td></tr>";

for ($t=0;$t<86400;$t=$t+60)
{
$saat=gmdate("H:i",$t);
$tm=gmdate("H:i",$t);
$file="./../triggers/time/".$mday."/".$tm."_files";
if (file_exists($file))
{ $gl=file_get_contents("./../triggers/time/".$mday."/".$tm."_files");
$gl=str_replace(".wait"," <font color=green><b>"._SECONDS." "._WAIT."</b></font>", $gl);
$gl=str_replace("#"," /", $gl);
$list.="<tr><td style='border-bottom:1px solid #cccccc'><table><tr><td><span id=\"btn_green\"  style='margin:2px;cursor:default'>".$tm."</span></td><td>".str_replace("\n","<br>",$gl)."</td></tr></table></td></tr>";
} else {$gl="";}

}
}
$list.="</table>";

echo $list;
include("tpl_controlpanel_footer.php");


?>