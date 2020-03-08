<?php
require ("./../admin/lang_turkish.php");
$DAY=date("w",time());
$now=date("H:i");
echo "Day Hour : $DAY $now";
if (file_exists("./../triggers/time/$DAY/$now"."_files"))
{
$say1=0;
$plist=trim(file_get_contents("./../triggers/time/$DAY/$now"."_files"));


$fget=trim(file_get_contents("./../triggers/closedtime"));
$fget=explode("\n",$fget);
if (in_array(date("d-m-Y"), $fget)) {
$plist="";	
}


$cdar=unserialize(file_get_contents("./../triggers/closeddays"));
$md=date("N",time());
if ($cdar["$md"]=="off")
{
$plist="";	
}



$statusres=trim(file_get_contents("./../triggers/playliststatus"));
if ($statusres!='refresh')
{
$plist="";	
}
$statusres="";

$statusres=trim(file_get_contents("./../triggers/nowlock"));
if ($statusres=='kill')
{
$plist="";	
}


if ($plist)
{
echo $plist."\n";
$plista=explode("\n",$plist);
$sayac=0;
foreach ($plista as $k=>$l)
{
$tim=explode("#",$l);
echo "$tim[0] -> $tim[1] \n ";
$path_info = pathinfo($tim[1]);
$ext=$path_info['extension'];	
$fname=$path_info['filename'];

if ((file_exists("./../sounds/$tim[1]")) OR ($ext=="wait"))
{

echo $tim[1]."\n";
$filetype="";
if ($ext=="wait")
{
$tim[1]=str_replace(".wait"," "._SECONDS." "._WAIT."", $tim[1]);
}
  if ($say1==0)
{
$runsh=true;
file_put_contents("./../triggers/belllist","#Starting File ".date("H:i:s")."\n");
file_put_contents("./../triggers/belllist","echo '$tim[1]' >./../triggers/nowplayingv \n",FILE_APPEND);
    $say1++;	
}
  
$sound=intval(trim(file_get_contents("./../triggers/volume_output")));
if (intval($tim[2])=='default')
{
$sound=intval(trim(file_get_contents("./../triggers/volume_output")));
} else {$sound=$tim[2];}


if ($tim[3]=='default')
{
$lentime="";
} else {$lentime=" -endpos 00:00:".intval($tim[3]);}


if ($ext!="wait")
{
file_put_contents("./../triggers/belllist","myplayersN.bin $lentime  -novideo -af volume=$sound.1:0 './../sounds/$tim[1]' \n",FILE_APPEND);
} else 
{
$tm=intval($fname);
file_put_contents("./../triggers/belllist","cd /rd-data/; sleep $tm\n",FILE_APPEND); 	
}
file_put_contents("./../triggers/belllist","echo >./../triggers/nowplayingv\n",FILE_APPEND);
}
}
if ($runsh)
{
system("sudo pkill -f nowlist > /dev/null 2>&1 &");
system("sudo pkill -f belllist > /dev/null 2>&1 &");
system("sudo pkill -f myplayerN-start > /dev/null 2>&1 &");
system("sudo pkill -f myplayersN.bin > /dev/null 2>&1 & ");
system("sudo echo >./../triggers/nowplayingv;");
system("sudo echo >./../triggers/nowlock;");
system("cd ./../triggers/;sh belllist >/dev/null 2>/dev/null &");		
}
}
}
?>
