<?php
require_once '../database/database_class.php';
require_once '../commonFunc.php';
include_once '../class/MailerClass.php';

$db = new Database();

$to = $_POST['changePw'];
$from = "Lubycon@gmail.com";
$subject = "Check your temporary password";
$password = "hmdwdgdhkr2015";

$tmpPw = makeToken(20);
$encrypt = password_hash($tmpPw, PASSWORD_DEFAULT);
echo $encrypt;

$FindPw = new MakeMail($tmpPw);
$FindPw->FindPw();

if(mailer($from, $to, $subject, $password, 'password'))
{
	$db->query = "UPDATE userbasic SET pass = '".$encrypt."' WHERE (email='".$to."')";
	if($db->askQuery() !== false){
		header('location:success_find_password.php');
	}else{
		echo "db 전송 실패";
	}

}else{
	echo "메일 전송 실패하였습니다.";
}



?>