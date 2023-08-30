<?php

echo '<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
<title>'._CONTROLPANEL.'</title>
<link href="'._THEME_STYLE.'" rel="stylesheet" type="text/css">
<style>
body {background:#ffffff}
</style>
<script language="javascript">

function focus()
{
document.login_form.username.focus(); 
}
</script>

<body onload="javascript:focus()">

';



echo '
<form action="index.php" method="post" name="login_form">
<div id="main">


<table class="maintable" style="height:400px;" cellpadding=20>
<tr><td>

<table align=center border=0 cellspacing=0 cellpadding=5  width=400px>
<tr><td colspan=2 align=center class=modulbaslik style="border-bottom:1px solid #c1c1c1">
<p><img src='._THEME_LOGO.' ></p>
<div class="ba">'._LOGINTITLE.'</div><br><font style="color:red;font-size:20px;font-weight:bold;">'.$_SERVER['HTTP_HOST'].'</font></br>
<span class="ab"><br>'._PLSLOGINWITHPASS.'</span>
</td></tr>
<tr><td align=center
style="border-right:1px solid #c1c1c1;
border-bottom:1px solid #c1c1c1;
border-left:5px solid #c1c1c1;
"><img src=./../img-panel/fingerprint.gif><br></td><td style="border-right:1px solid #c1c1c1;
border-bottom:1px solid #c1c1c1;
">
<table align=center border=0 cellspacing=0 cellpadding=3 bgcolor=#ffffff width=100%>
<tr><td>'._USERNAME."</td></tr>
<tr><td><input type=text id=\username\" name=username maxlength=20 size=15></td></tr>
<tr><td>"._PASSWORD.'</td></tr>
<tr><td><input type=password name=password maxlength=20 size=15></td></tr>

<tr><td>'._LANGUAGES.'</td></tr>
<tr><td>'.option_list(['english' => _ENGLISH, 'turkish' => _TURKISH], 'language', $userlang).'</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td><input type=submit name=sublogin value='._LOGIN.'></td></tr>
</table>
</td></tr></table>
<center>';



echo '
<br></font><font style="color:#cccccc;font-size:10px"t>'._ADBOX_VERSION.'</font></font>
</center>
</form></div>
</td></tr></table>
<br>
</div>
';
