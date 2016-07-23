<?php
require_once "../Class/session_class.php";
require_once "../Class/json_class.php";
require_once "../Class/session_class.php";


$json_control = new json_control;
$session = new Session();

$json_control->json_decode('job',"../../../data/job.json");
$job_json_Code = $json_control->json_decode_code;
$json_control->json_decode('country',"../../../data/country.json");
$country_json_Code = $json_control->json_decode_code;

	if($session->SessionExist()){

		$Loginuser_name = NULL;
		$Loginuser_id = NULL;
		$Loginuser_code = NULL;
		$Loginuser_job = NULL;
		$Loginuser_city = NULL;
		$Loginuser_country = NULL;

		if(isset($_SESSION['lubycon_validation']))
		{
			$activity = NULL;

			if($_SESSION['lubycon_validation'] === "active")
				$activity = true;
				else if($_SESSION['lubycon_validation'] === "inactive")
					$activity = false;
					else
						$activity = false;

						if($activity === false)
							$BODY_URL = "service/view/waiting_for_resisting.php";

		}else{
			$session->DestroySession();
		}

		$LoginState = true;
		$Loginuser_name = (isset($_SESSION['lubycon_nick']) === true) ? $_SESSION['lubycon_nick'] : NULL;
		$Loginuser_id = (isset($_SESSION['lubycon_id']) === true) ? $_SESSION['lubycon_id'] : NULL;
		$Loginuser_code = (isset($_SESSION['lubycon_userCode']) === true) ? $_SESSION['lubycon_userCode'] : NULL;
		$Loginuser_country = (isset( $_SESSION['lubycon_countryCode']) === true) ? $country_json_Code[$_SESSION['lubycon_countryCode']]['name'] : NULL;
		$Loginuser_job = (isset($_SESSION['lubycon_jobCode']) === true) ? $job_json_Code[$_SESSION['lubycon_jobCode']]['name'] : NULL;
		$Loginuser_city = (isset($_SESSION['lubycon_city']) === true) ? $_SESSION['lubycon_city'] : NULL;
		// login menu
		$public_key = (isset($_SESSION['lubycon_public_key']) === true) ? $_SESSION['lubycon_public_key'] : NULL;
		
		$session_data = array(
			'LoginState' => $LoginState,
			'username' => $Loginuser_name,
			'usercode' => $Loginuser_code,
			'country' => $Loginuser_country,
			'job' => $Loginuser_job,
			'city' => $Loginuser_city,
			'public_key' => $public_key
		);
		
		$data_json = json_encode($session_data);
		echo $data_json;
	}else{
		$LoginState = false;
		$data_json = json_encode($LoginState);
		echo $data_json;
	}



?>