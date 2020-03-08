<?php
require ("sessionstart.php");
if ($_SESSION[_SESSIONPERMPREFIX.'auth'] != "yes")
{
header("Location:"._PERMERRORURL);
die();	
}	

require ("config.php");

$PAGETITLE=_CLOSEDTIME;
$HEADINJECTION='
<script src="./../files/js/jquery.min.js"></script>
<link rel="stylesheet" href="./../files/js/datepicker/dist/datepicker.css">';
include ("tpl_controlpanel.php");

echo "
<div class='ba'>
"._CLOSEDTIME."</div><br>";


if ($_POST[submit]==_APPLY_CHANGES)
{
$datem=$_POST['date'];
$cdays=$_POST[day];
file_put_contents("./../triggers/closeddays",serialize($cdays));
if (is_array($_POST[delete])) foreach ($_POST[delete] as $key=>$del)
{
unset($datem[$key]);
file_put_contents("./../triggers/closedtime",implode("\n",$datem));

}
}

if ($_POST[submit]==_ADD)
{
$date=$_POST[datepicker];
$md=explode("-",$date);
if  (checkdate ( $md[1],$md[0],$md[2]))
{
$ndate=$md[2].$md[1].$md[0];

$fget=trim(file_get_contents("./../triggers/closedtime"));
$fget.="\n".$date;
$fgetnew=myfilesort_date($fget);
file_put_contents("./../triggers/closedtime",implode("\n",$fgetnew));
if ($date==date("d-m-Y"))
{
file_get_contents("http://127.0.0.1/admin/ajax_play_now.php?stat=stop");
}
echo "<p><font color=green><b>"._PAGE_SAVED."</b></font></p>";

}  else echo "<p><font color=red><b>"._DATETIME_ERROR."</b></font></p>";

}

$list="<form method=\"post\" id=\"adv_form\" name=\"adv_form\" action=\"" . $_SERVER["SCRIPT_URI"] . "\" enctype=\"multipart/form-data\">
<input type=\"hidden\" name=\"durum\" value=\"islendi\">
";

$DAYS=array(_DAY0,_DAY1,_DAY2,_DAY3,_DAY4,_DAY5,_DAY6,_DAY0,_DAY_ALL);
$list.="<table><tr><td></td><td></td></tr>";
$cdar=unserialize(file_get_contents("./../triggers/closeddays"));
for ($t=1;$t<8;$t++)
{

$list.="<tr><td>$DAYS[$t]</td><td>".optionlist("day[$t]",$cdar[$t])."</td></tr>";

}
$list.="<table>
<p><div class=line>&nbsp;</div></p>
";

$fget=trim(file_get_contents("./../triggers/closedtime"));
$list.="<table><tr><td>"._DELETE."</td><td>"._DATE."</td><td></td></tr>";
$datelist=array_unique(explode("\n",$fget));
$sayac=0;
$DAYS=array(_DAY0,_DAY1,_DAY2,_DAY3,_DAY4,_DAY5,_DAY6,_DAY0,_DAY_ALL);

foreach ($datelist as $dt){
if ($dt)
{
$dt_g=date("w",strtotime($dt));
$list.= "<tr><td><input type='checkbox' name='delete[$sayac]' style='margin:1px'></td>";
$list.= "<td>$dt <input type=\"hidden\" name=\"date[$sayac]\" value=\"$dt\"></td><td>".$DAYS[$dt_g]."</td>
</tr>";
$sayac++;
}
}
$list.="<table>";

$list.="<p>".form_button("submit",_APPLY_CHANGES,50)."</p>";


$ldtp="tr-TR";

$list.="
<p><div class=line>&nbsp;</div></p>
<table>
<tr>
<td>
<input type=\"text\" name=\"datepicker\" class=\"form-control\" data-toggle=\"datepicker\" style=\"width:100px;\">
".form_button("submit",_ADD,50)."
</td></tr>

</table>
</p></form>

<script src=\"./../files/js/datepicker/dist/datepicker.js\"></script>
<script src=\"./../files/js/datepicker/i18n/datepicker.tr-TR.js\"></script>
<script src=\"./../files/js/datepicker/i18n/datepicker.en-US.js\"></script>
<script>
$(function() {
$('[data-toggle=\"datepicker\"]').datepicker({
autoHide: true,
zIndex: 2048,
language: '$ldtp',
format: 'dd-mm-yyyy'
});
});
</script>
";


echo $list;
include("tpl_controlpanel_footer.php");


function myfilesort_date($fget)
{
$fget=preg_replace('/^[ \t]*[\r\n]+/m', '', $fget);
$fget=explode("\n",$fget);
foreach($fget as $fn)
{
if (trim($fn)) $myf[]=strtotime($fn);
}
asort($myf);

foreach ($myf as $mf)
{
$myfnew[]=date("d-m-Y",$mf);
}
return array_unique($myfnew);

}




function optionlist($name,$value)
{
$line="<select name=\"$name\">";
if ($value=="on")
{
$line.="<option value=\"on\" selected>"._OPEN."</option>";
$line.="<option value=\"off\">"._CLOSED."</option>";
} else
{
$line.="<option value=\"on\">"._OPEN."</option>";
$line.="<option value=\"off\" selected>"._CLOSED."</option>";

}

$line.="</select>";
return $line;
}

?>