<?php 
$server_side_sock = "/var/config/server1.sock";
system("rm -f $server_side_sock");
$socket = socket_create(AF_UNIX, SOCK_DGRAM, 0);
if (!$socket)
        die('Unable to create AF_UNIX socket');

if (!socket_bind($socket, $server_side_sock))
        die("Unable to bind to $server_side_sock");

system("chown www-data:www-data $server_side_sock");
while(1)
{
if (!socket_set_block($socket))
        die('Unable to set blocking mode for socket');
$buf = '';
$from = '';
echo "Ready to receive...\n";
$bytes_received = socket_recvfrom($socket, $buf, 65536, 0, $from);
if ($bytes_received == -1)
        die('An error occured while receiving from the socket');

//echo $buf;
if (trim($buf)=="play nowlist")
{
shell_exec("cd ./../../triggers/; sh nowlist >/dev/null 2>/dev/null &");
}
if (trim($buf)=="play belllist")
{
shell_exec("cd ./../../triggers/; sh belllist >/dev/null 2>/dev/null &");
}
if (trim($buf)=="stop")
{
system("pkill -f belllist > /dev/null 2>&1 &");
system("pkill -f nowlist > /dev/null 2>&1 &");
system("pkill -f myplayerN-start > /dev/null 2>&1 &");
system("pkill -f myplayersN.bin > /dev/null 2>&1 & ");
system("echo >./../../triggers/nowplayingv;");
system("echo >./../../triggers/nowlock;");
}


sleep(0.5);

}
