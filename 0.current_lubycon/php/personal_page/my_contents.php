<ul>
    <?php
        for($i=0;$i<60;$i++)
        {
            $_GET["number"] = $i;
            $category_param = 'my_contents';
            $web_depth = '../..';
            include('../layout/content_card.php');
        }
    ?>
</ul>
