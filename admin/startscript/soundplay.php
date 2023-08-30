<?php

require './../lang_turkish.php';
$DAY = date('w', time());
$now = date('H:i');
echo "Day Hour : $DAY $now";
if (file_exists("./../../triggers/time/$DAY/$now".'_files')) {
    $say1  = 0;
    $plist = trim(file_get_contents("./../../triggers/time/$DAY/$now".'_files'));


    $fget = trim(file_get_contents('./../../triggers/closedtime'));
    $fget = explode("\n", $fget);
    if (in_array(date('d-m-Y'), $fget)) {
        $plist = '';
    }


    $cdar = unserialize(file_get_contents('./../../triggers/closeddays'));
    $md   = date('N', time());
    if ($cdar["$md"] == 'off') {
        $plist = '';
    }



    $statusres = trim(file_get_contents('./../../triggers/playliststatus'));
    if ($statusres != 'refresh') {
        $plist = '';
    }

    $statusres = '';

    $statusres = trim(file_get_contents('./../../triggers/nowlock'));
    if ($statusres == 'kill') {
        $plist = '';
    }


    if ($plist) {
        echo $plist."\n";
        $plista = explode("\n", $plist);
        $sayac  = 0;
        foreach ($plista as $k => $l) {
            $tim = explode('#', $l);
            echo "$tim[0] -> $tim[1] \n ";
            $path_info = pathinfo($tim[1]);
            $ext       = $path_info['extension'];
            $fname     = $path_info['filename'];

            if ((file_exists("./../../sounds/$tim[1]")) or ($ext == 'wait')) {
                echo $tim[1]."\n";
                $filetype = '';
                if ($ext == 'wait') {
                    $tim[1] = str_replace('.wait', ' '._SECONDS.' '._WAIT.'', $tim[1]);
                }

                if ($say1 == 0) {
                    $runsh = true;
                    file_put_contents('./../../triggers/belllist', '#Starting File '.date('H:i:s')."\n");
                    // file_put_contents("./../../triggers/belllist","echo '$tim[1]' >./../triggers/nowplayingv \n",FILE_APPEND);
                    $say1++;
                }

                $sound = intval(trim(file_get_contents('./../../triggers/volume_output')));
                if (intval($tim[2]) == 'default') {
                    $sound = intval(trim(file_get_contents('./../../triggers/volume_output')));
                } else {
                    $sound = $tim[2];
                }


                if ($tim[3] == 'default') {
                    $lentime = '';
                } else {
                    $lentime = ' -endpos 00:00:'.intval($tim[3]);
                }

                file_put_contents('./../../triggers/belllist', "echo '$tim[1]' >./../triggers/nowplayingv \n", FILE_APPEND);
                if ($ext != 'wait') {
                    file_put_contents('./../../triggers/belllist', "myplayersN.bin $lentime  -novideo -af volume=$sound.1:0 './../sounds/$tim[1]' \n", FILE_APPEND);
                } else {
                    $tm = intval($fname);
                    file_put_contents('./../../triggers/belllist', "sleep $tm\n", FILE_APPEND);
                }

                file_put_contents('./../../triggers/belllist', "echo >./../triggers/nowplayingv\n", FILE_APPEND);
            }//end if
        }//end foreach

        if ($runsh) {
            send_sock('stop');
            send_sock('play belllist');
        }
    }//end if
}//end if

function send_sock($command)
{
    $socket = socket_create(AF_UNIX, SOCK_DGRAM, 0);
    if (!$socket) {
        die('Unable to create AF_UNIX socket');
    }

    $server_side_sock = '/var/config/server1.sock';
    $msg              = "$command";
    $len              = strlen($msg);
    $bytes_sent       = socket_sendto($socket, $msg, $len, 0, $server_side_sock);

}//end send_sock()
