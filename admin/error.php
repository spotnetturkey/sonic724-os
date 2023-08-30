<?php
require 'sessionstart.php';
?>
<html><head>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
<style>
BODY,P,DIV,INPUT,TEXTAREA,FORM,TD,FONT,A {
font-size:11px;
font-family:Verdana, Arial, Helvetica, sans-serif;
}
.ab {COLOR:red;margin-top:5px;font-weight:bold;font-size:13px;FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif}
.ba {COLOR:#999999;margin-top:5px;font-weight:bold;font-size:18px;FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif}
textarea,input,select {
background-color: #eeeeee;
border: 1px solid #BBBBBB;
padding: 2px;
margin: 1px;
font-size: 11px;
color: #000000;
}
</style>
<title>Error</title></head>
<body>
<br>
<br>
<center>
<div class="ab"><?php echo _DONTHAVEPERMISSION; ?></div>
<br>
<FORM><INPUT type=button value="<<Back " class="btn" onClick="history.back();">
<INPUT type=button value="Login Screen>>" class="btn" onClick="location.href='index.php'">
</FORM> 
</center>
</body>
</html>