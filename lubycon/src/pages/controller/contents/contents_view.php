<?php
$number = $_GET["conno"]; //contenst number form url
$cate = $_GET['cate'];
$url_parse = parse_url($_SERVER['HTTP_REFERER']);
if( isset($url_parse['query']) )
{
    $devide_query = (string)$url_parse['query'];
    setcookie('contents_history', $devide_query.'&conno='.$number.'&concate='.$cate, time()+(60*60*3),'/'); //3 hour cookie (for infinite scroll)
    setcookie("contents_hit-$cate-$number", 'conno='.$number.'&concate='.$cate.'&userip='.$_SERVER['REMOTE_ADDR'], time()+(60*60*6),'/'); //6 hour cookie (board hit)
}
//echo $_SERVER['REMOTE_ADDR'];
//echo $_SERVER['HTTP_REFERER'];
//echo $url_parse['query'];
//print_r( $url_parse);
$one_depth = '../..'; //css js load
$two_depth = '..'; // php load
include_once('../layout/index_header.php');

$allow_array = ['all','artwork','vector','threed'];
if( in_array($cate , $allow_array) )
{
    require_once '../database/database_class.php';
    $db = new Database();

    switch($cate){ //check category
    case 'artwork' : $contents_cate = 1; $cate_name = 'artwork'; break;
    case 'vector' : $contents_cate = 2; $cate_name = 'vector'; break;
    case 'threed' : $contents_cate = 3; $cate_name = 'threed'; break;
    default : $contents_cate = 1; break;
    }
}else
{
    include_once('../../404.php');
    die();
};

$db->changedb('lubyconboard');
if( isset($_COOKIE["contents_hit-$cate-$number"]) )
{
    parse_str($_COOKIE["contents_hit-$cate-$number"] , $cookie_parse );
    if( $cookie_parse['userip'] != $_SERVER['REMOTE_ADDR']  )
    {
        $db->query = "UPDATE `$cate_name` SET `viewCount` = `viewCount`+1 WHERE `$cate_name`.`boardCode` = $number";
        $db->askQuery(); // viewcount up
    }
}else
{
    $db->query = "UPDATE `$cate_name` SET `viewCount` = `viewCount`+1 WHERE `$cate_name`.`boardCode` = $number";
    $db->askQuery(); // viewcount up
}

$db->query =
"
SELECT *

FROM lubyconboard.`$cate_name` as a
LEFT JOIN lubyconuser.`userbasic` 
ON a.`userCode` = `userbasic`.`userCode` 

LEFT JOIN lubyconuser.`userinfo`
ON `userbasic`.`userCode` = `userinfo`.`userCode` 

LEFT JOIN lubyconboard.`".$cate_name."midcategory`
ON a.`boardCode` = `".$cate_name."midcategory`.`boardCode`

LEFT JOIN lubyconboard.`".$cate_name."tag`
ON a.`boardCode` = `".$cate_name."tag`.`boardCode`";
if($LoginState)
{
$db->query .= "LEFT JOIN lubyconboard.`contentsbookmark`
ON a.`boardCode` = `contentsbookmark`.`boardCode`
AND `contentsbookmark`.`bookmarkActionUserCode` = $Loginuser_code

LEFT JOIN lubyconboard.`contentslike`
ON a.`boardCode` = `contentslike`.`boardCode`
AND `contentslike`.`likeActionUserCode` = $Loginuser_code";
}
$db->query .= " WHERE a.`boardCode` = $number";
$db->askQuery(); //get db data

$row = mysqli_fetch_array($db->result);
if( !is_array($row) )
{
    include_once('../../404.php');
}

require_once "../class/json_class.php";
$json_control = new json_control;

$json_control->json_decode('job',"$one_depth/data/job.json");
$job_decode = $json_control->json_decode_code;
$json_control->json_decode('country',"$one_depth/data/country.json");
$country_decode = $json_control->json_decode_code;
$json_control->json_decode("$cate_name"."_category","$one_depth/data/middle_category.json");
$mid_cate_decode = $json_control->json_decode_code;
$json_control->json_decode("ccCode","$one_depth/data/ccCode.json");
$cc_code_decode = $json_control->json_decode_code;

$my_job_origin_select = $job_decode[$row["jobCode"]]['name'];
$my_country_origin_select = $country_decode[$row["countryCode"]]['name'];

$contents_name = $row['contentTitle'];
$contents_html = $row['contents'];
$user_img_url = $two_depth."/../../../Lubycon_Contents/user/".$row['userCode']."/profile.jpg";
$category0 = $cate_name == "threed" ? "3D" : $cate_name;
$category1 = $mid_cate_decode[$row['midCategoryCode0']]['name'];
$category2 = '';
$category3 = '';
if( isset( $row['midCategoryCode1'] ) ) $category2 = ' , '.$mid_cate_decode[$row['midCategoryCode1']]['name'];
if( isset( $row['midCategoryCode2'] ) ) $category3 = ' , '.$mid_cate_decode[$row['midCategoryCode2']]['name'];

$usercode = $row['userCode'];
$username = $row['nick'];
$usercity = $row['city'];

$userjob = $my_job_origin_select;
$usercountry = $my_country_origin_select;

$file_descript = $row['contentDescription'];
if($LoginState)
{
$like_check = false;
if( $row['likeActionUserCode'] != null ){$like_check=true;}
$bookmark_check = false;
if( $row['bookmarkActionUserCode'] != null  ){$bookmark_check=true;}
}
$file_view = $row['viewCount'];
$file_down = $row['downloadCount'];
$file_like = $row['likeCount'];

$file_path = $row['userDirectory'];

$cc_code = $row['ccCode'];
$json_control->json_search($cc_code_decode,'url','ccCode',$cc_code);
$cc_url = $json_control->search_key;

$tag_sum = '';
$tagnum = 0;
while( isset($row['tag'.$tagnum]) )
{
    $tag_sum .= "<li class='tagbox'>".$row['tag'.$tagnum]."</li>";
    $tagnum++;
}

$db->query = 
"
SELECT * 
FROM lubyconboard.`contentscomment` as a

RIGHT JOIN lubyconuser.`userbasic` as b
ON a.`commentActionUserCode` = b.`userCode`

LEFT JOIN lubyconuser.`userinfo` as c
ON b.`userCode` = c.`userCode`

WHERE a.`boardCode` = '$number'
AND a.`topCategoryCode` = '$cate'
AND a.`commentStatus` = 'normal';
";
$db->askQuery();
$comment_result = $db->result;

include_once("viewer2d.php");
include_once($two_depth.'/layout/index_footer.php');
?>

<script type="text/javascript" src="<?=$one_depth?>/js/thumbs_control.js"></script> <!-- account file js -->
<script type="text/javascript" src="<?=$one_depth?>/js/call_comments.js"></script> <!-- account file js -->
<link href="<?=$one_depth?>/css/contents_view.css" rel="stylesheet" type="text/css" /><!-- contents view css -->