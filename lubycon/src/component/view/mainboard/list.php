<?php
$number = $row['boardCode'];
$subject = $row['title'];
$comment_num = 0; //not yet

$like_num = $row['likeCount'];
$view_num = $row['viewCount'];
$content_date = "00.00.00"; // not yet



$username = 'not yet'; //not yet
$userimg = "../../ch/img/no_img/no_img_user1.jpg"; //not yet


$current_url = $row['boardCode'];

switch($current_url){
case 1 : $current_url = "forum"; break;
case 2 : $current_url = "tutorial";  break;
case 3 : $current_url = "qna";  break;
default : $current_url = "forum";  break;
};

echo "<li class='table_list'>
    <div class='table_list_inner'>
        <span class='table_number_wrap hidden-mb-ib'>
            <span class='table_blank'><i class='fa fa-circle' id='{$current_url}_circle'></i></span>   <!-- space -->
            <span class='table_number'>{$number}</span>   <!-- number -->
        </span>
        <span class='table_user_img'>
            <img src='{$userimg}'>
        </span>
        <span class='table_info'>
            <a href='../community/community_view.php?cate={$current_url}&bno={$number}'>  
                <span class='table_subject'>{$subject}</span>   <!-- subject -->
                <span class='table_contents'>[{$comment_num}]</span>
            </a>
            <span class='table_writer'>{$username}</span>    <!-- writer -->
        </span>
        <div class='table_counts'>
            <span class='table_date'>{$content_date}</span>    <!-- date -->
            <span class='table_view'><i class='fa fa-eye mobile_i visible-mb'></i>{$view_num}</span>    <!-- view -->
            <span class='table_like'><i class='fa fa-heart mobile_i visible-mb'></i>{$like_num}</span> <!-- like -->
        </div>
    </div>
</li>";
?>