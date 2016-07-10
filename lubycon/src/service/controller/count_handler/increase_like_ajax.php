<?php
require_once './common/Class/session_class.php';
    //session_start();
    $session = new Session();

    if(($session->GetSessionId() == null) && $session->GetSessionName() == null){
        $LoginState = false;
    }else{
        if($session->SessionExist()){
            
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
                    echo '<script>document.location.href="./php/account/waiting_for_resisting.php"</script>';

            }else{
                $session->DestroySession();
            } 

            $LoginState = true;
            
            $Loginuser_name = isset($_SESSION['lubycon_nick']) ? $_SESSION['lubycon_nick'] : NULL;
            $Loginuser_code= isset($_SESSION['lubycon_userCode']) ? $_SESSION['lubycon_userCode'] : NULL;
            // login menu
        }else{
            $LoginState = false;    
        }
    }
if($LoginState)
{
    $ajax_cate_name = $_POST['cate']; //artwork vector threed
    $ajax_countkind = $_POST['countkind']; //bookmark or like
    $ajax_countkind_name = $ajax_countkind.'Count'; //colume name
    $ajax_stat_check = $_POST['stat_check']; // status
    $ajax_nummber = $_POST['conno']; // contents boradCode
    $ajax_usercode = $Loginuser_code; // userCode

    require_once "../class/json_class.php";
    $json_control = new json_control;
    $json_control->json_decode('top_category',"../data/top_category.json");
    $top_cate_json = $json_control->json_decode_code;
    $json_control->json_search($top_cate_json,'topCateCode','name',$ajax_cate_name);
    $cate_name = $json_control->search_key;

    $content_kind = $_POST['contentkind'];
    $active_date = date("YmdHis");

    require_once './common/Class/database_class.php';
    $db = new Database();
    $db->query = 
        "
        select * from `lubyconboard`.`contents$ajax_countkind`
        WHERE `contents$ajax_countkind`.`".$ajax_countkind."ActionUserCode` = '$ajax_usercode'
        and `contents$ajax_countkind`.`boardCode` = '$ajax_nummber'
        and `contents$ajax_countkind`.`topCategoryCode` = '$cate_name'
        and `contents$ajax_countkind`.`".$ajax_countkind."BoardKind` = '$content_kind'
        ";
    $db->askQuery();
    //echo $db->query ;
    $select_result =  $db->result;
    //print_r($select_result->num_rows);
    

    if( $select_result->num_rows == 0 )
    {
        
        $stat_check = '+1';
        //echo $ajax_countkind.$ajax_usercode.$ajax_nummber.$cate_name;
        $db->query =
        "
        INSERT INTO `lubyconboard`.`contents$ajax_countkind`
        ( `".$ajax_countkind."ActionUserCode`, `boardCode`, `topCategoryCode`, `".$ajax_countkind."BoardKind`, `".$ajax_countkind."Date`) VALUES
        ( '$ajax_usercode', '$ajax_nummber', '$cate_name', '$content_kind', '$active_date');
        ";
    }else if ($select_result->num_rows <= 1 )
    {
        $stat_check = '-1';
        $db->query =
        "
        DELETE FROM
        `lubyconboard`.`contents".$ajax_countkind."`
        WHERE `contents".$ajax_countkind."`.`".$ajax_countkind."ActionUserCode` = '$ajax_usercode'
        and `contents".$ajax_countkind."`.`boardCode` = '$ajax_nummber'
        and `contents".$ajax_countkind."`.`topCategoryCode` = '$cate_name'
        and `contents".$ajax_countkind."`.`".$ajax_countkind."BoardKind` = '$content_kind'
        ";
    }else
    {
        die('select query is wrong');
    }
    echo $db->query;
    $db->askQuery(); 
    echo $db->database->error; 
    $db->query = "UPDATE `lubyconboard`.`$ajax_cate_name` SET `$ajax_countkind_name` = `$ajax_countkind_name` $stat_check WHERE `$ajax_cate_name`.`boardCode` = $ajax_nummber";
    $db->askQuery(); 
    
    echo $db->query;
}else
{
    return 'not login';
}
?>