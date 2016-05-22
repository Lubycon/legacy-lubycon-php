<?php
class infinite_scroll extends json_control
{
    private $page_kinds;
    private $top_category;
    private $top_cate_decode;
    private $middle_category;
    private $now_page;
    private $target_page;
    private $call_page;
    private $page_boundary;
    private $page_limit = 30;

    private $allow_array_list;
    private $allow_array_content = ['all','artwork','vector','threed'];
    private $allow_array_community = ['all','forum','tutorial','qaa'];

    public $query;
    public $query_foundRow = "SELECT FOUND_ROWS()";
    
    public $all_page_count; //all page count

	public function __construct($kinds,$top_category)
    {
		// default arg is page kind (content or community etc..)
		$this->page_kinds = $kinds;
		$this->top_category = $top_category;
        $this->page_boundary = $this->page_limit;

        if($this->page_kinds == 'content') //set allow array form page kinds
        {
            $this->allow_array_list = $this->allow_array_content;
        }else if($this->page_kinds == 'community')
        {
            $this->allow_array_list = $this->allow_array_community;
        }

        $this->json_decode('top_category',"../../data/top_category.json"); //extended code
        $this->top_cate_decode = $this->json_decode_code; //top category decode
	}

    public function validate_category() //check top category form allow array
    {
        if( in_array($this->top_category , $this->allow_array_list) )
        {
        }else
        {
            include_once('../error/404.php');
            die('dose not allow category name');
        }
    }

    public function set_option($page_number,$middle_category,$ajax_boolean,$ajax_page) //set query from user needs
    {
        // 0.call page number (param.1) 1. page boundary (class setting)
        // 2.top_category ($this->top_category) 3.middle cateroy (param.2 later)
        // 4.sort (param.3.array)
        // 5.search engine (later)
        $this->now_page = $page_number;
        if($ajax_boolean) //ajax
        {
            if( $this->now_page >= 1 )
            {
                $this->target_page = $ajax_page;
                $this->call_page = $page_number * $this->page_limit;
                $this->page_boundary = $this->page_limit; //call 1page (prev or next page)
            }
        }else if(!$ajax_boolean) //not ajax (page refresh)
        {
            $this->target_page = $this->now_page;
            if( $this->now_page > 1 )
            {
                $this->call_page = ( $this->now_page - 1 ) * $this->page_limit;
                $this->page_boundary = $this->page_limit * 3; //call 3page (prev,now,next page)
            }else if($this->now_page == 1)
            {
                $this->call_page = 0; //page 1
                $this->page_boundary = $this->page_limit * 2; //call 2page (now,next page)
            }
        }
        $this->middle_category = $middle_category; //later
    }

    public function set_query()
    {
        if( $this->top_category == 'all' )
        {
            $this->query = "SELECT SQL_CALC_FOUND_ROWS * FROM lubyconboard.`artwork` INNER join lubyconuser.`userbasic` INNER join lubyconuser.`userinfo` ON `artwork`.`userCode` = `userbasic`.`userCode` and `userbasic`.`userCode` = `userinfo`.`userCode` UNION SELECT * FROM lubyconboard.`vector` INNER join lubyconuser.`userbasic` INNER join lubyconuser.`userinfo` ON `vector`.`userCode` = `userbasic`.`userCode` and `userbasic`.`userCode` = `userinfo`.`userCode` UNION SELECT * FROM lubyconboard.`threed` INNER join lubyconuser.`userbasic` INNER join lubyconuser.`userinfo` ON `threed`.`userCode` = `userbasic`.`userCode` and `userbasic`.`userCode` = `userinfo`.`userCode` ORDER BY `boardCode` DESC limit $this->call_page,$this->page_boundary";
        
        }else
        {
            $this->query = "SELECT SQL_CALC_FOUND_ROWS * FROM lubyconboard.`$this->top_category` , lubyconuser.`userbasic` , lubyconuser.`userinfo` WHERE lubyconboard.`$this->top_category`.`userCode` = lubyconuser.`userbasic`.`userCode` AND lubyconuser.`userbasic`.`userCode` = lubyconuser.`userinfo`.`userCode`ORDER BY lubyconboard.`$this->top_category`.`boardCode` DESC limit $this->call_page,$this->page_boundary";
        }
    }

    public function count_page($db_result)
    {
        $foundRow_result = mysqli_fetch_array($db_result); //row count
        $this->all_page_count = ceil($foundRow_result[0] / 30); //all page count
    }


    public function spread_contents($contents_result,$one_depth)
    {
        if($contents_result->num_rows != 0)
        {
            //echo "<div class='scroll_checker page_top_$page_param'></div>";
            while( $row = mysqli_fetch_array($contents_result) )
            {
                $top_category = $this->top_cate_decode[$row['CategoryCode']];
                include('../layout/content_card.php');
            }   
            echo "<div class='scroll_checker page_bottom_$this->target_page'></div>";
        }else
        {
            echo "<p class='finish_contents'>no more contents :)</p>";
        }
    }

    public function check_cookie()
    {
        if( isset($_COOKIE['contents_history']))
        {
            $cookie_string = $_COOKIE['contents_history'];
            parse_str ($cookie_string , $cookie_parse );
            $cookie_contents_number = $cookie_parse['concate'].'_'.$cookie_parse['conno'];
            if( $this->top_category == $cookie_parse['cate'] && $this->now_page == $cookie_parse['page'])
            {
                echo "<script>scroll_from_cookie('$cookie_contents_number');</script>"; //find pre click contents
            }else
            {
                unset($_COOKIE['contents_history']); //delete cookie
            }
        }
    }
}
?>