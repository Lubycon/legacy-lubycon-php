<?php
require_once '../../../common/Class/session_class.php';
    //session_start();
    $session = new Session();

    if(($session->GetSessionId() == null) && $session->GetSessionName() == null){
        $LoginState = false;
    }else{
        if($session->SessionExist()){

            if(isset($_SESSION['lubycon_validation']))
            {
                $LoginState = true;
            }else{
                $session->DestroySession();
            }
            $Loginuser_name = isset($_SESSION['lubycon_nick']) ? $_SESSION['lubycon_nick'] : NULL;
            $Loginuser_code= isset($_SESSION['lubycon_userCode']) ? $_SESSION['lubycon_userCode'] : NULL;
            // login menu
        }else{
            $LoginState = false;
        }
    }
    require_once "../../../common/class/json_class.php";
    $json_control = new json_control;


if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $postData = json_decode(file_get_contents("php://input"));
}else
{
    die('it is not post data error code 0000');
}



if($LoginState)
{
    switch($postData->type)
    {
        case 0 : $countType = 'bookmark'; break;
        case 1 : $countType = 'like'; break;
        case 2 : $countType = 'view'; break;
        case 3 : $countType = 'comment'; break;
        case 4 : $countType = 'upload'; break;
        case 5 : $countType = 'download'; break;
        default : die('error code 1100'); break;
    }

    switch($postData->contentKind)
    {case 0 : $contentsKind = 'contents'; break;
        case 1 : $contentsKind = 'community'; break;
        default : die('error code 1101'); break;
    }
    $contentKindName = $contentsKind.'Count'; //colume name

    $json_control->json_decode($contentsKind.'_top_category',"../../../../data/top_category.json");
    $topCateJson = $json_control->json_decode_code;
    $number = $postData->conno; // contents boradCode
    $topCate = $postData->topCate; // 0 1 2
    $topCateName = $topCateJson[$topCate]['name'];
    $giveUserCode = $Loginuser_code; // userCode
    $takeUserCode = $postData->takeUser; // userCode

    $activeDate = date("YmdHis");

    require_once '../../model/count_handler/count_model.php';

}else
{
    die('error code 0101');
}
?>
