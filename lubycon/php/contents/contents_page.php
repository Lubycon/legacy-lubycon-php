<?php
    /* include layout (imfortant) */
    $one_depth = '../..'; //css js load
    $two_depth = '..'; // php load
    include_once('../layout/index_header.php');
    /* include layout (imfortant) */

    /* require class set value */
    require_once "$two_depth/database/database_class.php";
    $db = new Database();
    require_once "../class/json_class.php";
    $json_control = new json_control;
    require_once "../class/infinite_scroll_class.php";

    /* require class */

    /* json control */
    $current_url = $_GET["cate"];
    $json_control->json_decode($current_url.'_category',"$one_depth/data/middle_category.json");
    $middle_cate_decode = $json_control->json_decode_code;
    /* json control */
?>
<script type="text/javascript" src="<?=$one_depth?>/js/module/infinite_scroll.js"></script> <!-- scroll js -->
<script type="text/javascript" src="<?=$one_depth?>/js/module/jquery.lubySlider.js"></script>
<script type="text/javascript" src="<?=$one_depth?>/js/thumbs_control.js"></script>

<link href="<?=$one_depth?>/css/contents_page.css" rel="stylesheet" type="text/css" />  <!-- contents page css -->
<link href="<?=$one_depth?>/css/module/lubySlider.css" rel="stylesheet" type="text/css" />  <!-- contents page css -->


<div class="main_figure_wrap hidden-mb-b">
    <figure>
        <div class="dark_overlay_small"></div>
        <h2>CONTENTS</h2>
    </figure>	<!-- end main_figure -->
</div>
<section class="container">
    <section class="navsel hidden-mb-b">
        <nav class="lnb_nav">
            <ul>
                <li class="nav_menu" id="all">
                    <a href="./contents_page.php?cate=all&page=1">All</a>
                </li>
                <li class="nav_menu" id="artwork">
                    <a href="./contents_page.php?cate=artwork&page=1">Artwork</a>
                </li>
                <li class="nav_menu" id="vector">
                    <a href="./contents_page.php?cate=vector&page=1">Vector</a>
                </li>
                <li class="nav_menu" id="threed"> 
                    <a href="./contents_page.php?cate=threed&page=1">3D</a>
                </li>
            </ul>
        </nav>  <!-- end lnb nav -->
    </section>  <!-- end section -->


    <section class="nav_guide">
        <div class="nav-wrapper">
            <select class="preferFilter" data-param="filter">
                <option selected="selected">Featured</option>
                <option>Recent</option>
                <option>Most Like</option>
                <option>Most Download</option>
                <option>Most Comment</option>
            </select>
            <select class="copyrightFilter" data-param="cc">
                <option>All License</option>
                <option>Free</option>
                <option>No Commercial</option>
                <option>No Distribution</option>
            </select>
            <select class="categoryFilter" data-param="mid_cate">
            <?php
                $json_control->json_spread_option($middle_cate_decode);
                echo $json_control->json_spread_wrap;
            ?>
            </select>
            <div id="sub_search_bar" class="search-bar">
                <div class="select-box">
                    <select class="searchFilter contents_search_filter" data-param="search_filter">
                        <option value="Title">Title</option>
                        <option value="Creator">Creator</option>
                        <option value="Tag">Tag</option>
                    </select>
                </div>
                <input type="text" class="search-bar-text contents_search_text" value="Enter the keyword" />
                <div class="search-btn">
                    <i class="fa fa-search"></i>
                </div>
            </div>
        </div><!--subnav_box end-->
    </section>
    <section id="contents_box" class="con_wrap">

    <input type="range" class="sliderKey" value="1" width="6" min='1' max="">
        <ul class="contents_wrap">
        </ul>
    </section>  <!-- end contents box -->
</section>  <!-- end contents section -->

<?php
    include_once($two_depth.'/layout/index_footer.php');
?>