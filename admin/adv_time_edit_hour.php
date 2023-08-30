<?php

require 'sessionstart.php';
require 'config.php';
$id = $_GET["id"];
$t  = 0;

echo '
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<script src="./../files/js/jquery.min.js"></script>
<link href="./themes/default/style.php" rel="stylesheet" type="text/css">

<script src="./../files/js/jquery.tablednd.1.0.3.min.js"></script>
<link rel="stylesheet" href="./../files/css/tablednd.css" type="text/css"/>
<style>
body {
background-color: #ffffff;
margin:10;
padding:10
}
</style>
<body>
';




$day   = $_GET['day'];
$timeh = $_GET['time'];

$DAYS = [
    _DAY0,
    _DAY1,
    _DAY2,
    _DAY3,
    _DAY4,
    _DAY5,
    _DAY6,
    _DAY0,
    _DAY_ALL,
];

echo "
<div class='ba'>
".$DAYS[$day].'-'.$timeh.'</div><br>';


// $filename=time().'_'.rand(0,1000).".text";
if ($_POST["submit"] == _APPLY_CHANGES) {
    $sr      = $_POST['s'];
    $pf      = $_POST['f'];
    $snd     = $_POST['sound'];
    $lnt     = $_POST['lenght'];
    $sortnew = explode(",",$_POST['sortnew'][1]);
  if (substr($_POST['sortnew'][1],-1)==",") array_pop($sortnew);
  $sortnew=array_flip($sortnew);
  
    if (is_array($_POST["delete"])) {
        foreach ($_POST["delete"] as $key => $del) {
            // echo "$key . $del";
            unset($pf[$key]);
            unset($sr[$key]);
            unset($snd[$key]);
            unset($lnt[$key]);
            unset($sortnew[$key]);
        }
    }
//print_r($_POST);
  //  print_r($snd);
  //   print_r($sortnew);
    array_multisort($sortnew, SORT_ASC, SORT_NUMERIC, $pf);
    array_multisort($sortnew, SORT_ASC, SORT_NUMERIC, $snd);
    array_multisort($sortnew, SORT_ASC, SORT_NUMERIC, $lnt);
    

$flista=array();
    foreach ($pf as $k => $f) {
        $flista[] = $sr[$k].'#'.$f.'#'.$snd[$k].'#'.$lnt[$k]."\n";
    }


    $fl = implode('', $flista);
    if ($fl) {
        file_put_contents("./../triggers/time/$day/".$timeh.'_files', $fl);
    } else {
        unlink("./../triggers/time/$day/".$timeh.'_files');
    }
}//end if



if ($_POST["submit"] == _ADD) {
    if (isset($_POST['dlist'])) {
        $filename = $_POST['dlist'];
        $lenght   = $_POST['lenght'];
        $sort     = $_POST['sort'];
        $sound    = $_POST['sound'];
    }


    $fget  = trim(file_get_contents("./../triggers/time/$day/".$timeh.'_files'));
    $fget .= "\n".$sort1.'#'.$filename.'#'.$sound.'#'.$lenght;


    $fgetnew = myfilesort($fget);
    file_put_contents("./../triggers/time/$day/".$timeh.'_files', implode('', $fgetnew));
}


if ($_POST["submit"] == _ADD_1) {
    if (isset($_POST['clist'])) {
        $filename = $_POST['clist'];
        $sort     = $_POST['csort'];
    }

    $fget  = trim(file_get_contents("./../triggers/time/$day/".$timeh.'_files'));
    $fget .= "\n".$sort1.'#'.$filename;


    $fgetnew = myfilesort($fget);
    file_put_contents("./../triggers/time/$day/".$timeh.'_files', implode('', $fgetnew));
}


$hlist = trim(file_get_contents("./../triggers/time/$day/".$timeh.'_files'));
if ($hlist) {
    $hlista = explode("\n", $hlist);
}

$sayac = 0;
foreach ($hlista as $k => $l) {
    $tim = explode('#', $l);

    if ($tim[0] > $vl) {
        $vl = $tim[0];
    }

    $path_info = pathinfo($tim[1]);
    $ext       = $path_info['extension'];
    $fname     = $path_info['filename'];
    if ($ext == 'wait') {
        $tim[2] = 'nosound';
    }

    $t1 = str_replace('.wait', ' '._SECONDS.' '._WAIT.'', $tim[1]);

    $st = 1;

    $hlistf .= "<tr style=\"border:1px solid #cccccc;\" id='$sayac'>
<td><input type='checkbox' name='delete[$sayac]' style='margin:1px'></td>
<td>".optionlist("sound[$sayac]", $tim[2]).'</td>
<td>'.optionlenght("lenght[$sayac]", $tim[3])."</td>
<td><input type=\"hidden\" name=\"f[$sayac]\" value=\"$tim[1]\">
$t1</td>
</tr>
";


    $textarea[] = $sayac;
    $sayac++;
}//end foreach

$st = 1;
if ($sayac >= 1) {
    $hlistf = "

<script type=\"text/javascript\">
var filelist=[];
$(document).ready(function() {
$(\"#table-$st\").tableDnD({
".'
onDragClass: "myDragClass",
onDrop: function(table, row) {

var rows = table.tBodies[0].rows;
var debugStr ="";
var mydb = "";
var val = "";
for (var i=1; i<rows.length; i++) {
var r=i-1;
var vl="sound["+r+"]";
var val1=document.getElementById(vl).value;
filelist[r]=rows[i].id;
debugStr+= rows[i].id + ",";
}
document.getElementById("ta_'.$st.'").value =debugStr;



}

});
'."  
});
</script>
<table id=\"table-$st\" style=\"border-collapse:collapse;\" cellpadding=3 cellspacing=3><tr class=\"nodrop\">
<th>"._DELETE.'</th><th><font>'._SOUND_STAT.'</font></th><th><font>'._LENGHT."</font><th></th></tr>
$hlistf</table>
<input type=\"hidden\" name=\"sortnew[$st]\" id=\"ta_$st\" size=\"20\" value='".implode(',', $textarea)."'>
";
}//end if



$resimdizin  = './../sounds/';
$dosyalistem = filelistdizin("$resimdizin");

if (is_array($dosyalistem)) {
    $collator = new Collator('tr_TR');
    $collator->asort($dosyalistem, Collator::SORT_STRING);
}

$dlist = "<select name='dlist' style=\"width:200px;\">";
foreach ($dosyalistem as $k => $d) {
    $d = str_replace('.png', '', $d);
    if ($d == $_POST["dlist"]) {
        $dlist .= "<option value=\"$d\" selected>$d</option>";
    } else {
        $dlist .= "<option value=\"$d\">$d</option>";
    }
}

$dlist .= '</select>';


$clist = "<select name='clist' style=\"width:350px;\">";
for ($t = 1; $t < 61; $t++) {
    $clist .= "<option value=\"$t.wait\">".$t.' '._SECONDS.' '._WAIT.'</option>';
}

$clist .= '</select>';
$list   = '<form method="post" id="adv_form" name="adv_form" action="'.$_SERVER['SCRIPT_URI']."\" enctype=\"multipart/form-data\">
<input type=\"hidden\" name=\"durum\" value=\"islendi\">

$hlistf
";


if ($sayac >= 1) {
    $addt = '<p>'.form_button('submit', _APPLY_CHANGES, 50).'</p>';
}

$vl    = ($vl + 10);
$list .= "
$addt
</form>
<form method=\"post\" id=\"adv_form2\" name=\"adv_form2\" action=\"".$_SERVER['SCRIPT_URI'].'" enctype="multipart/form-data">

<p><INPUT type="hidden" NAME="sort" VALUE="'.($vl).'" SIZE=5>&nbsp;'.optionlist('sound', "$sound").optionlenght('lenght', "$lenght").$dlist.form_button('submit', _ADD, 50).'<p><INPUT type="hidden" NAME="csort" VALUE="'.($vl).'" SIZE=5>&nbsp;'.optionlist('sound', 'nosound').$clist.form_button('submit', _ADD_1, 50).'<p>

</form>';


echo $list;


function optionlenght($name, $value)
{
    $line = "<select name=\"$name\" id=\"$name\" style=\"width:150px;\">";

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
    if ($value == 'nosound') {
        $line  = "<select class=mydom name=\"$name\"  id=\"$name\" style=\"width:110px;\" >";
        $line .= '<option value="default">'._NO_SOUND.'</option>';
    } else {
        $line = "<select name=\"$name\"  id=\"$name\" style=\"width:110px;\">";
    }

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


function myfilesort($fget)
{
    $fget = explode("\n", $fget);
    // return $fget;
    $filelist   = [];
    $sortlist   = [];
    $lenghtlist = [];
    foreach ($fget as $k => $l) {
        if (trim($l)) {
            $tim          = explode('#', $l);
            $filelist[]   = $tim[1].'#'.$tim[2];
            $sortlist[]   = $tim[0];
            $lenghtlist[] = $tim[3];
            // $soundlist[]=$tim[2];
            $mlist[$tim[0]][] = $tim[1].'#'.$tim[2].'#'.$tim[3];
            $sayac++;
        }
    }

    foreach ($mlist as $k => $l) {
        $mydizim[$k] = $l;
    }

    foreach ($mydizim as $k => $af) {
        foreach ($af as $l) {
            $fgetnew[] = $k.'#'.$l."\n";
        }
    }

    return $fgetnew;

}//end myfilesort()


function CheckExt($filename, $ext)
{
    $passed     = false;
    $file_parts = pathinfo($filename);
    if ($file_parts['extension'] == "$ext") {
        $passed = true;
    }

    return $passed;

}//end CheckExt()


function filelistdizin($dizin)
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

}//end filelistdizin()
