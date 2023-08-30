<?php

require 'sessionstart.php';
// print_r($_SESSION);
if ($_SESSION[_SESSIONPERMPREFIX.'auth'] != 'yes') {
    header('Location:'._PERMERRORURL);
    die();
}

require 'config.php';

$DAYS = [
    _DAY0,
    _DAY1,
    _DAY2,
    _DAY3,
    _DAY4,
    _DAY5,
    _DAY6,
];

$DAY = $_GET['day'];

$HEADINJECTION = '
<link href="uploadfile.css" rel="stylesheet">
<script src="./../files/js/jquery.min.js"></script>
<script src="jquery.uploadfile.min_'.$userlang.'.js"></script>
<script src="./../files/js/jquery.colorbox.js"></script>
<link rel="stylesheet" href="./../files/css/colorbox.css" />
<script>
$(document).ready(function(){
//Examples of how to assign the Colorbox event to elements

$(".resetcon").colorbox({iframe:true, width:"80%", height:"80%"});	

$(".resetcon").colorbox({
onClosed:function(){ window.location = window.location.href; }
});

});
</script>




<script type="text/javascript">
$(document).ready(function(){
$(\'#dailyres\').click(function(){
var ischecked = $(\'#dailyres\').is(":checked");

$.ajax({
type: \'POST\',
url: \'ajax_playlist_status_datetime.php?check=\'+ischecked,
success: function(data) {
// alert(data);
$(\'#m1\').html(data);
}
});
});
});

</script>

<script type="text/javascript">
//var pliststatus="null";
$(document).ready(function(){
$(\'#btn_green\').click(function(){
var myOpts = document.getElementById(\'dlist\').value;
var lenghtt= document.getElementById(\'lenght\').value;
var snd=document.getElementById(\'sound\').value;
$.ajax({
type: \'POST\',

url: \'ajax_play_now.php?stat=\'+pliststatus+\'&vol=\'+snd+\'&file=\'+myOpts+\'&lenght=\'+lenghtt,
success: function(data) {

button1();
}
});
});
});

function button1()
{
if (pliststatus == \'play\')
{

pliststatus="play";

$(\'#btn_green\').css("background-color","green");
$(\'#btn_green\').css("font-size","20px");

$(\'#btn_green\').css("color","white");

$(\'#btn_green\').html(\''._START_NOW.'\'); 


} else
{

pliststatus="play";

$(\'#btn_green\').css("background-color","green");
$(\'#btn_green\').css("font-size","20px");

$(\'#btn_green\').css("color","white");

$(\'#btn_green\').html(\''._START_NOW.'\'); 

}


}
</script>


';



$PAGETITLE = _PLAYLIST_EDIT.'-'.$DAYS[$DAY];
require 'tpl_controlpanel.php';

echo "
<div class='ba'>
"._PLAYLIST_EDIT.'-'.$DAYS[$DAY].'</div><br>';


if ($_GET['op'] == 'select') {
    file_put_contents('./../triggers/selecthour', $_GET['day'].','.$_GET['h']);
}

if ($_GET['op'] == 'dselect') {
    file_put_contents('./../triggers/selectday', $_GET['day']);
}

if ($_GET['op'] == 'dpaste') {
    $sh = strval(trim(file_get_contents('./../triggers/selectday')));
    // echo strval($sh);
    if (strval($sh) != '') {
        $srcd = './../triggers/time/'.$sh.'/*';
        $cpd  = './../triggers/time/'.$_GET['day'].'/.';
        system("rm $cpd/* -r");
        $cmd1 = "cp $srcd $cpd -R";
        system($cmd1);
    }
}


if ($_GET['op'] == 'paste') {
    $sh = explode(',', file_get_contents('./../triggers/selecthour'));
    // print_r($sh);
    if ($sh[1]) {
        $srcd = './../triggers/time/'.$sh[0].'/'.$sh[1];
        // echo $srcd;
        $cpd = './../triggers/time/'.$_GET['day'].'/'.$_GET['h'].'_files';
        // echo "cp ".$srcd."_".$tsc."_files $cpd";
        system('cp '.$srcd.'_files '.$cpd);
    }
}

if ($_GET['op'] == 'delete') {
    $sh = explode(',', file_get_contents('./../triggers/selecthour'));
    // print_r($sh);
    if ($sh[1]) {
        $srcd = './../triggers/time/'.$sh[0].'/'.$sh[1];
        // echo $srcd;
        $cpd = 'rm ./../triggers/time/'.$_GET["day"].'/'.$_GET["h"].'_files';
        // echo "cp ".$srcd."_".$tsc."_files $cpd";
        system("$cpd");
    }
}




if ($_POST['submit_apply'] == _APPLY_CHANGES) {
    $sortnew       = $_POST['sort'];
    $videotimenew  = $_POST['videotime'];
    $aktifpasifnew = $_POST['aktifpasif'];
    $timenew       = $_POST['time'];

    $resimdizin = './../sounds/';

    if (is_array($_POST['delete'])) {
        foreach ($_POST['delete'] as $key => $del) {
            unlink("$resimdizin/$key");
            unlink("$resimdizin/$key.png");
            unlink("$resimdizin/$key.jpg");
            unlink("$resimdizin/$key.info");

            unset($timenew["$key"]);
            unset($sortnew["$key"]);
            unset($videotimenew["$key"]);
        }
    }

    $newarray[0] = $sortnew;
    $newarray[1] = $videotimenew;
    $newarray[2] = $aktifpasifnew;



    $data_file = base64_encode(serialize($newarray));
    file_put_contents('./../triggers/dailyvideo/ADV/sortvideo_'.$DAY, $data_file);

    $data_file = base64_encode(serialize($timenew));
    file_put_contents('./../triggers/dailyvideo/ADV/time_'.$DAY, $data_file);

    $sortimage  = $sortnew;
    $videotime  = $videotimenew;
    $aktifpasif = $aktifpasifnew;
    $time       = $timenew;
}//end if



echo filelistresim($DAY);
require 'tpl_controlpanel_footer.php';


function filelistresim($day)
{
    global $sortimage,$videotime,$aktifpasif,$timelist,$time,$DAYS;

    $playfile = trim(file_get_contents('./../triggers/nowplayingv'));
    $playlock = trim(file_get_contents('./../triggers/nowlock'));

    $resimdizin  = './../sounds/';
    $dosyalistem = filelistdizin1("$resimdizin");

    if (is_array($dosyalistem)) {
        $collator = new Collator('tr_TR');
        $collator->asort($dosyalistem, Collator::SORT_STRING);
    }

    // asort($dosyalistem);
    $dlist = "<select id='dlist' name='dlist' style='font-size:20px;width:450px;margin:3px'>";
    foreach ($dosyalistem as $k => $d) {
        $d = str_replace('.png', '', $d);
        if ($d == $playfile) {
            $dlist .= "<option value=\"$d\" selected>$d</option>";
        } else {
            $dlist .= "<option value=\"$d\">$d</option>";
        }
    }

    $dlist .= '
</select>';

    if (($playlock == 'kill') and ($playfile)) {
        $dlist = optionlist('sound', 'default').optionlenght('lenght', 'default')."<br><a id=\"btn_green\" style='margin:3px;padding:3px;'>"._PLAY_NOW.'&nbsp;</a>
<script>
var pliststatus="stop";
button1();
</script>'.$dlist.'
<br>';
    } else {
        $dlist = '
'.optionlist('sound', 'default').optionlenght('lenght', 'default').'<br>'."<a id=\"btn_green\" style='margin-top:7px;padding:3px;'>"._PLAY_NOW.'&nbsp;</a>
<script>
var pliststatus="stop";
button1();
</script>
'.$dlist.'<br> ';
    }

    $statusres = trim(file_get_contents('./../triggers/playliststatus'));

    if ($statusres == 'refresh') {
        $list .= '<p>'.$dlist.'</p>
<p>
<input type="checkbox" name="dailyres" id="dailyres" value="enabled" checked>
<span id=m1><font style="font-size:16px;color:green;font-weight:bold">'._PLAYLIST_STATUS_ENABLED.'</font></span> </p>
';
    } else {
        $list .= '
<p>'.$dlist.'</p>
<p>
<input type="checkbox" name="dailyres" id="dailyres" value="disabled">
<span id=m1><font style="font-size:16px;color:red;font-weight:bold">'._PLAYLIST_STATUS_DISABLED.'</font></span> </p>';
    }

    $list .= "<style>
.image { 
position: relative; 
width: 100%; /* for IE 6 */
}

h2 { 
position: absolute; 
top: 50px; 
left: 0; 
width: 100%; 
}
.resims {border:1px solid #cccccc}
</style>

<script>
$(document).on('click', ':not(form)[data-confirm]', function(e){
if(!confirm($(this).data('confirm'))){
e.stopImmediatePropagation();
e.preventDefault();
}
});
$(document).on('click', ':not(form)[copy-confirm]', function(e){
if(!confirm($(this).data('confirm'))){
e.stopImmediatePropagation();
e.preventDefault();
}
});

</script>
<style>
.str_on	  {background-color:green;padding:2px;border:1px solid #cccccc;text-align:center;text-weight:bold;font-size:12px;color:white}
.str_off	{background-color:red;margin:4px;padding:2px;border:1px solid #cccccc;text-align:center;text-weight:bold;font-size:12px;color:white}	
.str_all  {background-color:blue;margin:4px;padding:2px;border:1px solid #cccccc;text-align:center;text-weight:bold;font-size:12px;color:white}
.td_1 {border:1px solid #cccccc; width:50px;vertical-align: top;}
</style>
";
    $list .= '<form method="post" id="adv_form" name="adv_form" action="'.$_SERVER['SCRIPT_URI'].'" enctype="multipart/form-data">
<input type="hidden" name="durum" value="islendi">
';
    $list .= '<p>';
    for ($t = 0; $t < 24; $t = ($t + 1)) {
        if ($t == '12') {
            $list .= '</p><p>';
        }

        $gd = gmdate('H:i', ($t * 60 * 60));

        if ($t == $_GET['h']) {
            $gd = '<font color=black>'.$gd.'</font>';
        }

        $list .= "
<a id=\"btn_green\"  style='margin:2px' href='adv_time_edit.php?day=".$_GET['day']."&h=$t'>".$gd.'</a>';
    }

    $list .= '</p>';

    $selecthour = explode(',', file_get_contents('./../triggers/selecthour'));
    $selectday  = trim(file_get_contents('./../triggers/selectday'));

    $list  .= "

<table width=80% border=0 style=\"border:1px solid #cccccc\"><tr><td style='border-bottom:1px solid #cccccc'>
".$DAYS[$selectday]."
<a id=\"btn_green\" href='adv_time_edit.php?op=dselect&day=$day&h=$t'>"._DAILY_COPY."</a>
<a id=\"btn_green\" href='adv_time_edit.php?op=dpaste&day=$day&h=$t' data-confirm='"._CONFIRM_PASTE."'>"._DAILY_PASTE.'</a><br>
<img src=./images/select.png height=20 border=0>'.$DAYS[$selecthour[0]].' - '.$selecthour[1].' 

</td></tr>';
    $numara = 0;
    $saat   = $_GET['h'];

    for ($t = ($saat * 60); $t < (($saat + 1) * 60); $t = ($t + 1)) {
        $tm  = gmdate('H:i', ($t * 60));
        $gl  = '';
        $ofp = './../triggers/time/'.$_GET['day'].'/'.$tm.'_files';
        if (file_exists($ofp)) {
            $gl = file_get_contents("$ofp");
        }

        $gl = str_replace('.wait', ' <font color=green><b>'._SECONDS.' '._WAIT.'</b></font>', $gl);
        $gl = str_replace('#', ' /', $gl);

        $list .= "<tr><td style='border-bottom:1px solid #cccccc'><table><tr><td width=130>
<a href='adv_time_edit.php?op=select&day=$day&h=$tm'><img src=./images/select.png height=20 border=0></a>
<a href='adv_time_edit.php?op=paste&day=$day&h=$tm' data-confirm='"._CONFIRM_PASTE."'><img src=./images/paste.png height=20 border=0></a>
<a href='adv_time_edit.php?op=delete&day=$day&h=$tm' data-confirm='"._CONFIRM_DELETE."'><img src=./images/delete.png height=20 border=0></a>
<a id=\"btn_green\" class='resetcon' href=\"./adv_time_edit_hour.php?day=$day&time=$tm\"> ".$tm.'</a></td><td>'.str_replace("\n", '<br>', $gl).'</td></tr></table></td></tr>';
    }

    $list .= '</table>
</form></p>

';

    return $list;

}//end filelistresim()


function optionlenght($name, $value)
{
    $line = "<select name=\"$name\" id=\"$name\" style=\"width:200px;font-size:16px;margin:3px\">";

    $line .= '<option value="default">'._DEFAULT_LENGHT.'</option>';
    $num   = 0;
    for ($t = 3; $t < 61; $t = ($t + 1)) {
        $vl = ($num + $t);
        if (strval($vl) == $value) {
            $line .= "<option value=\"$vl\" selected>$vl</option>";
        } else {
            $line .= "<option value=\"$vl\">$vl</option>";
        }
    }

    $num = 60;
    for ($t = 5; $t < 301; $t = ($t + 5)) {
        $vl = ($num + $t);
        if (strval($vl) == $value) {
            $line .= "<option value=\"$vl\" selected>$vl</option>";
        } else {
            $line .= "<option value=\"$vl\">$vl</option>";
        }
    }

    $line .= '</select>';
    return $line;

}//end optionlenght()


function optionlist($name, $value)
{
    $line = "<select name=\"$name\" id=\"$name\" style=\"width:200px;font-size:16px;margin:3px\">";

    $line .= '<option value="default">'._DEFAULT_SOUND.'</option>';

    $num = -10;
    for ($t = 0; $t < 21; $t = ($t + 1)) {
        $vl = ($num + $t);
        if (strval($vl) == $value) {
            $line .= "<option value=\"$vl\" selected>$vl</option>";
        } else {
            $line .= "<option value=\"$vl\">$vl</option>";
        }
    }

    $line .= '</select>';
    return $line;

}//end optionlist()


function CheckExt($filename, $ext)
{
    $passed     = false;
    $file_parts = pathinfo($filename);
    if ($file_parts['extension'] == "$ext") {
        $passed = true;
    }

    return $passed;

}//end CheckExt()


function filelistdizin1($dizin)
{
    $exts = [
        'mp3',
        'wav',
    ];
    $dir  = opendir("$dizin");

    // $files = readdir($dir);
    while (false !== ($files = readdir($dir))) {
        foreach ($exts as $value) {
            if (CheckExt($files, $value)) {
                // echo "<a href=\"$files\">$files</a>\n";
                $dosyalist[] = $files;
                $count++;
                break;
            }
        }
    }

    closedir($dir);
    return $dosyalist;

}//end filelistdizin1()
