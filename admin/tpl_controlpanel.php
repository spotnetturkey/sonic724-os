<?php

require 'tpl_css.php';
$nowtime   = time();
$starttime = date('Y-m-d H:i:s', $nowtime);
$findme    = 'jquery.min.js';
$pos       = strpos($HEADINJECTION, $findme);
if ($pos === false) {
    echo '<script src="jquery.min.js"></script>';
}

echo $HEADINJECTION;
echo "<title>$PAGETITLE</title>";
if ($PAGEREFRESH) {
    echo "<meta http-equiv=\"refresh\" content=\"$PAGEREFRESH\">";
}

echo '</head><body>';
// print_r($_POST);
$tabletop           .= '<table class="tabletop"><tr><td align=left width="200"><img src='._THEME_LOGO.'>
</td><td align=left>';
$tabletop           .= "</td><td> 
<div id='dateplaying'></div>
<a id='playbutton' style='cursor:pointer;'></a><span id='nowplaying' style='font-size:20px'></span>
<span id='closetoday' style='font-size:20px'></span>
</font>
<script>
function loadclosetext()
{
$('#closetoday').load('closetoday.php',function () {});

}
function loadlink(){
$('#dateplaying').load('dateplaying.php',function () {});
$('#nowplaying').load('nowplaying.php',function () {});
var pl = $('#nowplaying').text();
if ($.trim(pl))
{
".'
$(\'#playbutton\').css("background-color","red");


$(\'#playbutton\').css("color","white");

$(\'#playbutton\').css("font-size","20px");

$(\'#playbutton\').css("font-weight","bold");

$(\'#playbutton\').css("padding","2px");

$(\'#playbutton\').css("border","1px solid #cccccc");


$(\'#playbutton\').html(\''._STOP_NOW.'\'); 
'."

} else
{
$('#playbutton').css(\"padding\",\"0px\");
$('#playbutton').css(\"border\",\"0px\");

$('#playbutton').text(''); 

}

// 
}
".'
$(document).ready(function(){
$(\'#playbutton\').click(function(){

$.ajax({
type: \'POST\',

url: \'ajax_play_now.php?stat=stop\',
success: function(data) {

}
});
});
});

'.'
loadclosetext();
loadlink(); // This will run on page load
setInterval(function(){
loadlink() // this will run after every 5 seconds
}, 1000);
</script>
';
$tabletop           .= '</td></tr></table>';
$MENUTITLE['br']     = _MENU_BROADCAST;
$MENUTITLE['config'] = _MENU_CONFIG;
$MENUTITLE['logout'] = _MENU_LOGOUT;
$MENU['br'][]        = [
    _NOW,
    'adv_time_edit.php?day='.date('w', time()).'&h='.date('H:i', (time() + 120)),
];
$MENU['br'][]        = [
    '<hr>',
    '',
];
$MENU['br'][]        = [
    _DAY1,
    'adv_time_edit.php?day=1',
];
$MENU['br'][]        = [
    _DAY2,
    'adv_time_edit.php?day=2',
];
$MENU['br'][]        = [
    _DAY3,
    'adv_time_edit.php?day=3',
];
$MENU['br'][]        = [
    _DAY4,
    'adv_time_edit.php?day=4',
];
$MENU['br'][]        = [
    _DAY5,
    'adv_time_edit.php?day=5',
];
$MENU['br'][]        = [
    _DAY6,
    'adv_time_edit.php?day=6',
];
$MENU['br'][]        = [
    _DAY0,
    'adv_time_edit.php?day=0',
];
$MENU['br'][]        = [
    '<hr>',
    '',
];
$MENU['br'][]        = [
    _WEEKLY_LIST,
    'adv_weekly_list.php',
];
$MENU['config'][]    = [
    _CLOSEDTIME,
    'adv_datetime_config.php',
];
$MENU['config'][]    = [
    _CHANGEPASSADMIN,
    'changepassword_admin.php',
];
$MENU['logout'][]    = [
    _LOGOUT,
    'logout.php',
];
$filecome            = str_replace('/admin/', '', $_SERVER['REQUEST_URI']);

$fl       = explode('?', $filecome);
$filecome = basename($_SERVER['REQUEST_URI']);
$filefull = basename($_SERVER['SCRIPT_NAME']);

$keycome = -1;
foreach ($MENU as $keys => $val) {
    $count = 0;
    foreach ($val as $val1) {
        $konum = strpos($val1[1], $filefull);
        if ($konum !== false) {
            $keycome = $keys;
        }
    }
}


// echo "**********".$keycome."***************";
$topmenu = '<ul>';

foreach ($MENU as $keys => $val) {
    $count = 0;
    foreach ($val as $val1) {
        if ($count == 0) {
            if ($keys == $keycome) {
                if ($val1[2] != 'no') {
                    $topmenu .= '<li><a href="'.$val1[1].'" class="active">'.$MENUTITLE[$keys].'</a></li>';
                    $count++;
                }
            } else {
                if ($val1[2] != 'no') {
                    $topmenu .= '<li><a href="'.$val1[1].'">'.$MENUTITLE[$keys].'</a></li>';
                    $count++;
                }
            }

            // echo "***>$keys - $keycome";
            // print_r($val1);
        }

        if (($keys == $keycome) and ($val1[2] != 'no')) {
            if (basename($val1[1]) == $filecome) {
                if ($filecome == 'quickpackage_auto.php') {
                    $mylinks = parse_url($val1[1]);
                    if ($mylinks[query] == 'code='.$_GET[code]) {
                        $sidemenu .= "<a href=\"$val1[1]\" class=\"selecta\">&nbsp;&raquo;&nbsp;".$val1[0].'</a>';
                    } else {
                        $sidemenu .= "<a href=\"$val1[1]\">&nbsp;&laquo;&nbsp;".$val1[0].'</a>';
                    }
                } else {
                    $sidemenu .= "<a href=\"$val1[1]\" class=\"selecta\">&nbsp;&raquo;&nbsp;".$val1[0].'</a>';
                }
            } else {
                if ($val1[0] == '<hr>') {
                    if ($val1[1]) {
                        $sidemenu .= '<div class="hs_header1">'.$val1[1].'</div>';
                    } else {
                        $sidemenu .= '<hr style="border:1px dotted #cccccc">';
                    }
                } else {
                    $sidemenu .= "<a href=\"$val1[1]\" >&nbsp;&laquo;&nbsp;".$val1[0].'</a>';
                }
            }//end if
        }//end if
    }//end foreach
}//end foreach

$topmenu .= '
</ul>';


echo "
<table cellspacing=0 cellpadding=0 width=100%><tr><td class=\"topheader\" style=\"background-color:#ffffff\">
$tabletop
";
echo "
<tr><td>
<div id=\"header\">
<div id=\"header_inner\" class=\"fixed\">

<div id=\"menu\">

$topmenu
</div>
";

echo '
</div>
</div>

</td></tr>
<tr><td>
<div id="main">
<div id="main_bottom">

<table class="maintable" style="height:100%">
<tr><td valign=top class="tdleft">
<div  class="blockleft">
<div class="hs_header1">'.$MENUTITLE[$keycome]."</div>
<div id=\"mn_a\" class=\"mn_a\">
$sidemenu

";

echo '
</div>
</div>
</td><td valign=top class="tdcenter">
';



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

file_put_contents('./../triggers/closew', "$msgw");


function file_basename($file=null)
{
    if ($file === null || strlen($file) <= 0) {
        return null;
    }

    $file     = explode('?', $file);
    $file     = explode('/', $file[0]);
    $basename = $file[(count($file) - 1)];

    return $basename;

}//end file_basename()


function customSearch($keyword, $arrayToSearch)
{
    foreach ($arrayToSearch as $key => $arrayItem) {
        if (stristr($arrayItem, $keyword)) {
            return $key;
        }
    }

}//end customSearch()
