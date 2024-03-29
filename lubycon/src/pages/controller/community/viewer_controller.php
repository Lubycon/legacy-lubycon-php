<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once "../../../common/Class/json_class.php";
$json_control = new json_control;
require_once "../../../common/Class/session_class.php";
$session = new Session();
if(($session->GetSessionId() == null) && $session->GetSessionName() == null){
	$LoginState = false;
}else{
	if($session->SessionExist()){
		$LoginState = true;
		$Loginuser_code= $_SESSION['lubycon_userCode'];
	}else{
		$LoginState = false;
	}
}
if(!isset($Loginuser_code)){$Loginuser_code='';} // not login stat , valuable is ''


if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	$postData = json_decode(file_get_contents("php://input"));
}
else{
	$total_array = array(
		'status' => array(
			'code' => '1200',
			'msg' => "nothing receive post data"
		),
		'result' => (object)array()
	);
	$data_json = json_encode($total_array);
	die($data_json);
}

$boardCode = $postData->bno;
$cateCode = (int)$postData->cate;


$json_control->json_decode('job',"../../../../data/job.json");
$job_decode = $json_control->json_decode_code;
$json_control->json_decode('country',"../../../../data/country.json");
$country_decode = $json_control->json_decode_code;


if( $cateCode < 3)
{
	//check category
	switch($cateCode)
	{
		case 0:
		$cate_name = 'forum';
		break;
		case 1 :
		$cate_name = 'tutorial';
		break;
		case 2 :
		$cate_name = 'qaa';
		break;
		default :
		die ('category code error 1001');
		break;
	}
}
else
{
	$total_array = array(
		'status' => array(
			'code' => '1001',
			'msg' => "not allow category code"
		),
		'result' => (object)array()
	);
	$data_json = json_encode($total_array);
	die($data_json);
}

include_once('../../model/community/viewer_model.php');

// contetnts data
$my_job_origin_select = $job_decode[$row["jobCode"]]['name'];
$my_country_origin_select = $country_decode[$row["countryCode"]]['name'];

$like_check = false;
$bookmark_check = false;
if($LoginState)
{
	if( $row['likeGiveUserCode'] != null )
	{
		$like_check=true;
	}
}

$contents_data = array(
	'title' => $row['contentTitle'],
	'content' => $row['contents'],
	'date' => $row['contentDate'],
	'like' => $like_check,
	'counter' => array(
		'view' => $row['viewCount'],
		'like' => $row['likeCount']
	),
	'file_path' => $row['userDirectory'],
);

// write user data
$write_user_data = array(
	'code' => $row['userCode'],
	'name' => $row['nick'],
	'job' => $my_job_origin_select,
	'country' => $my_country_origin_select,
	'city' => $row['city'],
	'profile' => $row['profileImg']
);

// comment data
$comment_data = array();
while($comment_row = mysqli_fetch_assoc($comment_result)){
	array_push(
		$comment_data,
		array(
			'usercode' => $comment_result['commentGiveUserCode'],
			'username' => $comment_result['nick'],
			'profile' => $comment_result['profileImg'],
			'date' => $comment_result['commentDate'],
			'content' => $comment_result['commentContents']
		)
	);
}
// commnet data


$total_array = array(
	'status' => array(
		'code' => '0000',
		'msg' => "community contents call succsess"
	),
	'result' => (object)array(
		'contents' => $contents_data,
		'creator' => $write_user_data,
		'comment' => $comment_data
	)
);
$data_json = json_encode($total_array);
die($data_json);
?>
