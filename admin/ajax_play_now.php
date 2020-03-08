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

send_sock("stop");
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
send_sock("stop");
send_sock("play nowlist");
}
} else {
send_sock("stop");
}
}

function send_sock($command)
{
$socket = socket_create(AF_UNIX, SOCK_DGRAM, 0);
if (!$socket)
        die('Unable to create AF_UNIX socket');

$server_side_sock = "/var/config/server1.sock";
$msg = "$command";
$len = strlen($msg);
$bytes_sent = socket_sendto($socket, $msg, $len, 0, $server_side_sock);
}

?>
