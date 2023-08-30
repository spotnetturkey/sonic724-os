<?php

require 'sessionstart.php';

$cdar = unserialize(file_get_contents('./../triggers/closeddays'));
$md   = date('N', time());
if ($cdar["$md"] == 'off') {
    $msgw = '<div style="background-color:red;color:white;font-size:20px;font-weight:bold;border:1px solid #cccccc;padding:3px;">'._CLOSEDTIME_WARNING.'</div>';
}

$fget = trim(file_get_contents('./../triggers/closedtime'));
$fget = explode("\n", $fget);
if (in_array(date('d-m-Y'), $fget)) {
    $msgw = '<div style="background-color:red;color:white;font-size:20px;font-weight:bold;border:1px solid #cccccc;padding:3px;">'._CLOSEDTIME_WARNING.'</div>';
}

echo $msgw;
