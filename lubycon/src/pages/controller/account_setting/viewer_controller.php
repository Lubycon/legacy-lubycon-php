<?php
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
require_once "../../../common/Class/json_class.php";
$json_control = new json_control;
$job_json = $json_control->json_decode('job',"../../../../data/job.json");
$job_decode = $json_control->json_decode_code;
$country_json = $json_control->json_decode('country',"../../../../data/country.json");
$country_decode = $json_control->json_decode_code;
/*data render setting*/

$usernumber = $_POST['usernum'];

if( $Loginuser_code === $usernumber )
{
	include '../../model/account_setting/model.php';

	$user_data = array(
			'email' => $userdata_row['email'],
			'nickname' => $userdata_row['nick'],
			'job' => $job_decode[$userdata_row['jobCode']]['name'],
			'position' => $userdata_row['company'],
			'location' => $country_decode[$userdata_row['countryCode']]['name'],
			'city' => $userdata_row['city'],
			'description' => $userdata_row['userDescription'],
			'mobile' => $userdata_row['telNumber'],
			'fax' => $userdata_row['fax'],
			'website' => $userdata_row['web'],
	);

	$user_language = array();
	while ($row = mysqli_fetch_array($language_result)) {
		$language_name = $row['languageName'];
		$user_language[] = $language_name;
	}

	$user_history = array();
	while ($row = mysqli_fetch_array($history_result)) {
		$historyYear = $row['historyDateYear'];
		$historyMonth = $row['historyDateMonth'];
		$historyCategory = strtolower(str_replace(' ', '_', $row['historyCategory']));
		$historyContents = $row['historyContents'];

		$user_history[] = array(
				'year' => $historyYear,
				'month' => $historyMonth,
				'category' => $historyCategory,
				'contents' => $historyContents
		);
	}

	$public_option = array(
			'email' => $userdata_row["emailPublic"],
			'mobile' => $userdata_row["mobilePublic"],
			'fax' => $userdata_row["faxPublic"],
			'web' => $userdata_row["webPublic"]
	);
	$total_array = array(
			'user_data' => $user_data,
			'user_language' => $user_language,
			'user_history' => $user_history,
			'public_option' => $public_option
	);

}else
{
	$total_array = array(
			'error_code' => 0001, // session and post user number not same
	);
}

$data_json = json_encode($total_array);
echo $data_json;
?>