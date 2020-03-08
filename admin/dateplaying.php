<?php

require ("sessionstart.php");
//require ("config.php");

$DAYS=array(_DAY0,_DAY1,_DAY2,_DAY3,_DAY4,_DAY5,_DAY6);

$DAY=date("w");

echo "<a href=\"adv_time_edit.php?day=".date("w",time())."&h=".date("H:i",time()+120)."\"><font style=\"font-size:20px\">".date("d-m-Y H:i:s")." - ". $DAYS[$DAY]."</font></a>";

?>

