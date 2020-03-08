<?php
require ("sessionstart.php");
if ($_SESSION[_SESSIONPERMPREFIX.'auth'] != "yes")
{
header("Location:"._PERMERRORURL);
die();
}
require ("config.php");
$file=$_GET[file];
$stat=$_GET[stat];
$vol=$_GET['vol'];
$lenght=$_GET['lenght'];
if (file_exists("./../sounds/$file"))
{
echo "File exists";
if ($stat=="play")
{
$runsh=true;

system("sudo pkill -f belllist > /dev/null 2>&1 &");
system("sudo pkill -f nowlist > /dev/null 2>&1 &");
system("sudo pkill -f myplayerN-start > /dev/null 2>&1 &");
system("sudo pkill -f myplayersN.bin > /dev/null 2>&1 & ");
system("sudo echo >./../triggers/nowplayingv;");
file_put_contents("./../triggers/nowlist","#Starting File ".date("H:i:s")."\n");
file_put_contents("./../triggers/nowlock","kill");
	
$path_info = pathinfo($file);
$ext=$path_info['extension'];	
$fname=$path_info['filename'];
$filetype="";
echo "Playing";
file_put_contents("./../triggers/nowlist","echo '$file' >./../triggers/nowplayingv \n",FILE_APPEND);
if (!isset($_GET['vol'])) {$vol="default";}

if ($vol=='default')
{
$sound=intval(trim(file_get_contents("./../triggers/volume_output")));
} else {$sound=$vol;}

if ($lenght=='default')
{
$lentime="";
} else {$lentime=" -endpos 00:00:$lenght ";}

file_put_contents("./../triggers/nowlist","myplayersN.bin $lentime -novideo -af volume=$sound.1:0 './../sounds/$file' \n",FILE_APPEND);
file_put_contents("./../triggers/nowlist","echo >./../triggers/nowplayingv\n",FILE_APPEND);
file_put_contents("./../triggers/nowlist","echo >./../triggers/nowlock \n",FILE_APPEND);

if ($runsh)
{
system("sudo pkill -f nowlist;sudo pkill -f myplayerN-start; sudo pkill -f myplayersN.bin;sudo echo >./../triggers/nowplayingv");
shell_exec("cd ./../triggers/;sudo chmod +x nowlist; sudo sh nowlist >/dev/null 2>/dev/null &");		
}
} else {
system("sudo pkill -f nowlist > /dev/null 2>&1 &");
system("sudo pkill -f belllist > /dev/null 2>&1 &");
system("sudo pkill -f myplayerN-start > /dev/null 2>&1 &");
system("sudo pkill -f myplayersN.bin > /dev/null 2>&1 & ");
system("sudo echo >./../triggers/nowplayingv;");
system("sudo echo >./../triggers/nowlock;");

}
}


?>
