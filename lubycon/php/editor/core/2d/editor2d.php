<?php
    //if(isset($_COOKIE)){
    //    if(isset($_COOKIE['login'])){
    //    }else if(!isset($_COOKIE['login'])){
    //        echo('<script>location.href="login_page.php"</script>');
    //    }
    //}
    $one_depth = '../../../..'; //css js load
    $two_depth = '../../..'; // php load
    include_once('../../../layout/index_header.php');
?>
<script>var PRESET_DEPTH = "../../../../"</script>
<link href="../../../../css/module/lubySlider.css" rel="stylesheet" type="text/css" />
<link href="../../module/css/spectrum.css" rel="stylesheet" type="text/css" />
<link href="../../module/css/cropper.css" rel="stylesheet" type="text/css" />
<link href="../../../../css/module/chosen.css" rel="stylesheet" type="text/css" />
<link href="../../module/css/editorShared.css" rel="stylesheet" type="text/css" />
<link href="./editor2d.css" rel="stylesheet" type="text/css" />
<link href="../../../../css/editor.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="../../../../data/module/icons.json"></script>
<script type="text/javascript" src="../../../../data/module/keyCode.json"></script>
<script type="text/javascript" src="../../data/categories.json"></script>
<script type="text/javascript" src="../../../../data/creative_commons.json"></script>

<script type="text/javascript" src="../../../../js/module/jquery.lubySlider.js"></script>
<script type="text/javascript" src="../../../../js/module/modalClass.js"></script>
<script type="text/javascript" src="../../module/js/spectrum.js"></script>
<script type="text/javascript" src="../../module/js/cropper.js"></script>
<script type="text/javascript" src="../../../../js/module/chosen.jquery.js"></script>
<script type="text/javascript" src="../../module/js/html2canvas.js"></script>
<script type="text/javascript" src="../../module/js/resizeObject.js"></script>
<script type="text/javascript" src="../../module/js/editorClasses.js"></script>
<script type="text/javascript" src="./editor2d.js"></script> 

<!-- editor css -->
<section id="editor-container" class="initEditor"></section>
<?php 
    $cate = $_GET['cate'];
    $contents_html = '';
    $usercode = $Loginuser_code;
    $username = $Loginuser_name;
    $usercity = $Loginuser_city;
    $usercountry = $Loginuser_country;
    $userjob = $Loginuser_job;
    $allow_array = ['all','artwork','vector','threed'];
    if( in_array($cate , $allow_array) )
    {
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



    echo "<div id='previewer'><span id='preview-close'>close</span>";
        include_once("../../../contents/viewer2d.php"); 
    echo "</div>";
?>

<?php
//php variable setting
    $contents_cate = $_GET["cate"];

    $allow_array = ['artwork','vector'];

    if( in_array($contents_cate , $allow_array) ){
        echo 
        '<script>
	        $("#editor-container").initEditor();
        </script>';
    }else{
        include_once('../../error/404.php');
    }
?>
<?php
    include_once($two_depth.'/layout/index_footer.php');
?>