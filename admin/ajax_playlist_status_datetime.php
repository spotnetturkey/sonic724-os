<?php

require 'sessionstart.php';
if ($_SESSION[_SESSIONPERMPREFIX.'auth'] != 'yes') {
    header('Location:'._PERMERRORURL);
    die();
}

require './../admin/config.php';
$check = $_GET[check];

if ($check == 'false') {
    file_put_contents('./../triggers/playliststatus', 'kill');
    echo '<span><font style="font-size:16px;color:red;font-weight:bold">'._PLAYLIST_STATUS_DISABLED.'</font></span>';
} else {
    file_put_contents('./../triggers/playliststatus', 'refresh');

    echo '<span><font style="font-size:16px;color:green;font-weight:bold">'._PLAYLIST_STATUS_ENABLED.'</font></span> </p>';
}
