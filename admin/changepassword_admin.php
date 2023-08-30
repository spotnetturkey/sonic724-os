<?php

require 'sessionstart.php';
if ($_SESSION[_SESSIONPERMPREFIX.'auth'] != 'yes') {
    header('Location:'._PERMERRORURL);
    die();
}

require 'config.php';
$PAGETITLE = _CHANGEPASSADMIN;
require 'tpl_controlpanel.php';
echo "
<div class='ba'>
"._CHANGEPASSADMIN.'</div><br>';

$nr          = 0;
$nowtime     = time();
$passwordold = (isset($_POST['passwordold'])) ? $_POST['passwordold'] : '';
$password1   = (isset($_POST['password1'])) ? $_POST['password1'] : '';
$password2   = (isset($_POST['password2'])) ? $_POST['password2'] : '';
$durum       = (isset($_POST['durum'])) ? $_POST['durum'] : '';





if ($durum == 'islendi') {
    $send         = 0;
    $passwordold1 = md5($passwordold);
    $pass         = trim(file_get_contents('/var/config/users'));
    ;
    if ($passwordold1 == $pass) {
        if ((strlen($password1) < 5) or (strlen($password2) < 5)) {
            $msg_password1 = '<font color=red>&nbsp;'._PASSLENGHTERR.'</font>';
            $send          = 1;
        }

        if ($password1 != $password2) {
            $msg_password2 = '<font color=red>&nbsp;'._NEWPASSNOTMATCH.'</font>';
            $send          = 1;
        }
    } else {
        $msg_passwordold = '<font color=red>&nbsp;'._OLDPASSNOTMATCH.'</font>';
        $send            = 1;
    }




    // <tr><td>Password</td><td>".form_line("password",$password,15).$msg_password."</td></tr>
    $inputtext = '<table>
<FORM METHOD="post"  name="password_form" ACTION="'.$_SERVER['REQUEST_URI'].'" enctype="multipart/form-data">
<tr><td>'._OLDPASSWORD.'</td><td>'.form_line_pass('passwordold', $passwordold, 15).$msg_passwordold.'</td></tr>
<tr><td>'._NEWPASSWORD.'</td><td>'.form_line_pass('password1', $password1, 15).$msg_password1.'</td></tr>
<tr><td>'._NEWPASSWORD.'</td><td>'.form_line_pass('password2', $password2, 15).$msg_password2.'</td></tr>

<tr><td></td><td>'.form_button('submit', _SEND, 50).'</td></tr>
<INPUT type="hidden" name="durum" value="islendi"></FORM>
</table> 	';

    if ($send) {
        echo $inputtext;
    } else {
        file_put_contents('/var/config/users', md5($password1));
        echo _PASSWORDCHANGED;
    }
} else {
    // $password=$MYDB[password];
    // print_r($data);
    $inputtext = '<table>
<FORM METHOD="post"  name="password_form" ACTION="'.$_SERVER['REQUEST_URI'].'" enctype="multipart/form-data">
<tr><td>'._OLDPASSWORD.'</td><td>'.form_line_pass('passwordold', $passwordold, 15).$msg_password.'</td></tr>
<tr><td>'._NEWPASSWORD.'</td><td>'.form_line_pass('password1', $password1, 15).$msg_password.'</td></tr>
<tr><td>'._NEWPASSWORD.'</td><td>'.form_line_pass('password2', $password2, 15).$msg_password.'</td></tr>

<tr><td></td><td>'.form_button('submit', _SEND, 50).'</td></tr>
<INPUT type="hidden" name="durum" value="islendi"></FORM>
</table> 	';


    echo $inputtext;
}//end if




require 'tpl_controlpanel_footer.php';
