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
    private $start_page;
    private $page_boundary;
    private $page_limit = 30;

    private $allow_array_list;
    private $allow_array_content = ['all','artwork','vector','threed'];
    private $allow_array_community = ['all','forum','tutorial','qaa'];

    private $filter; // array
    private $sort; // array

    public $where_query = " WHERE a.`contentStatus` = 'normal' AND "; // for query
    public $order_query = ' ORDER BY a.`contentDate` DESC '; // for query
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

        $this->json_decode('top_category',"../../../../data/top_category.json"); //extended code
        $this->top_cate_decode = $this->json_decode_code; //top category decode
	}

    public function validate_category() //check top category form allow array
    {
        if( in_array($this->top_category , $this->allow_array_list) )
        {
        }else
        {
            include_once('../../../service/view/error/404.php');
            die('dose not allow category name');
        }
    }

    public function set_option($filter,$sort,$page_number,$ajax_boolean,$ajax_page) //set query from user needs
    {
        // 0.call page number (param.1) 1. page boundary (class setting)
        // 2.top_category ($this->top_category) 3.middle cateroy (param.2 later)
        // 4.sort (param.3.array)
        // 5.search engine (later)

        $this->filter = $filter;
        $this->sort = $sort;

        $null_count = 0;
        foreach( $this->filter as $key => $value )
        {
            if( $this->filter[$key]['value'] != null )
            {
                print_r($this->filter[$key]['value']);
                $this->where_query .= $this->filter[$key]['query']." and ";
            }else
            {
                $null_count++;
            }
        }
        $this->where_query = substr($this->where_query, 0, -4);//delete last and string

        switch($sort)
        {
            case 0 : $this->order_query = " ORDER BY a.`viewCount` DESC "; break;
            case 1 : $this->order_query = " ORDER BY a.`contentDate` DESC "; break;
            case 2 : $this->order_query = " ORDER BY a.`likeCount` DESC "; break;
            case 3 : $this->order_query = " ORDER BY a.`downloadCount` DESC "; break;
            case 4 : $this->order_query = " ORDER BY a.`commentCount` DESC "; break;
            default : $this->order_query = " ORDER BY a.`contentDate` DESC "; break;
        }

        $this->now_page = $page_number;
        if($ajax_boolean) //ajax
        {
            if( $this->now_page >= 0 )
            {
                $this->target_page = $ajax_page;
                $this->call_page = ($page_number - 1) * $this->page_limit;
                $this->page_boundary = $this->page_limit; //call 1page (prev or next page)
            }
        }
        /*else if(!$ajax_boolean) //not ajax (page refresh)
        {
            $this->target_page = $this->now_page;
            if( $this->now_page > 1 )
            {
                $this->start_page = $this->now_page - 1;
                $this->call_page = ( $this->now_page - 2 ) * $this->page_limit;
                $this->page_boundary = $this->page_limit * 3; //call 3page (prev,now,next page)
            }else if($this->now_page == 1)
            {
                $this->start_page = 1;
                $this->call_page = 0; //page 1
                $this->page_boundary = $this->page_limit * 2; //call 2page (now,next page)
            }
        }*/
    }

    public function set_query($query_user_code)
    {
        if( $this->top_category == 'all' )
        {
            $this->query = "
            SELECT SQL_CALC_FOUND_ROWS 
            a.`boardCode`,a.`userCode`,a.`topCategoryCode`,a.`contentTitle`,a.`userDirectory`,a.`ccLicense`,a.`downloadCount`,a.`commentCount`,a.`viewCount`,a.`likeCount`, c.`nick`, a.`midCategoryCode0`, a.`midCategoryCode1`, a.`midCategoryCode2` ,a.`contentStatus`";
            if(isset($query_user_code))
            {$this->query .= " ,b.`bookmarkActionUserCode` ";}
            $this->query .= 
            " FROM 
            ( 
                SELECT * FROM lubyconboard.`artwork`
                LEFT JOIN lubyconboard.`artworkmidcategory`
                USING (`boardCode`)
    
                UNION SELECT * FROM lubyconboard.`vector` 
                LEFT JOIN lubyconboard.`vectormidcategory`
                USING (`boardCode`)
    
                UNION SELECT * FROM lubyconboard.`threed` 
                LEFT JOIN lubyconboard.`threedmidcategory`
                USING (`boardCode`)
            ) AS a ";
            if(isset($query_user_code))
            {
            $this->query .= "LEFT JOIN lubyconboard.`contentsbookmark` AS b 
            ON a.`boardCode` = b.`boardCode`
            AND a.`topCategoryCode` = b.`topCategoryCode`
            AND b.`bookmarkActionUserCode` = $query_user_code ";
            }
            $this->query .= "LEFT JOIN lubyconuser.`userbasic` AS c 
            ON a.`userCode` = c.`userCode` 

            $this->where_query
            $this->order_query
            limit $this->call_page,$this->page_boundary";
        
        }else
        {
            $this->query = "
            select SQL_CALC_FOUND_ROWS
            a.`boardCode`,a.`userCode`,a.`topCategoryCode`,a.`contentTitle`,a.`userDirectory`,a.`ccLicense`,a.`downloadCount`,a.`commentCount`,a.`viewCount`,a.`likeCount`, c.`nick`, a.`midCategoryCode0`, a.`midCategoryCode1`, a.`midCategoryCode2` ,a.`contentStatus`";
            if(isset($query_user_code))
            {$this->query .= " ,b.`bookmarkActionUserCode` ";}
            $this->query .= 
            " from 
                (
                SELECT * FROM lubyconboard.`$this->top_category`                 
                LEFT JOIN lubyconboard.`$this->top_category"."midcategory`
                USING (`boardCode`)
                ) as a
            ";
            if(isset($query_user_code))
            {
                $this->query .= 
                "LEFT JOIN lubyconboard.`contentsbookmark` b
                ON a.`boardCode` = b.`boardCode`
                AND a.`topCategoryCode` = b.`topCategoryCode`
                AND b.`bookmarkActionUserCode` = $query_user_code";
            }
            $this->query .= " left join lubyconuser.`userbasic` c
            ON a.`userCode` = c.`userCode`
            
            $this->where_query
            $this->order_query
            limit $this->call_page,$this->page_boundary";
        }
        //echo $this->query;
    }

    public function count_page($db_result)
    {
        $foundRow_result = mysqli_fetch_array($db_result); //row count
        $this->all_page_count = ceil($foundRow_result[0] / 30); //all page count
    }


    public function spread_contents($contents_result,$one_depth,$ajax_boolean)
    {
        if($contents_result->num_rows != 0)
        {
            //echo "<div class='scroll_checker page_top_$page_param'></div>";
            $i = 1;
            while( $row = mysqli_fetch_array($contents_result) )
            {
                $this->json_decode('top_category',"../../../../data/top_category.json");
                $country_decode = $this->json_decode_code;
                $this->json_decode('ccCode',"../../../../data/ccCode.json");
                $ccCode_decode = $this->json_decode_code;
                $top_category = $country_decode[$row['topCategoryCode']]['name'];
                include('../../../component/view/contents_card/content_card.php');

                /*page load*/
                if($i == $this->page_limit && !$ajax_boolean)
                {
                    echo "<div class='scroll_checker page_bottom_$this->start_page'></div>";
                    $i = 1;
                    $this->start_page++;
                }else
                {
                    $i++;
                }
                /*page load*/
            }   
            /*ajax*/
            if($ajax_boolean)
            {
                echo "<div class='scroll_checker page_bottom_$this->target_page'></div>";
            }
            /*ajax*/

            if($this->all_page_count == $this->target_page){
                echo '<div class="finish_contents" data-value="content"></div>';
            }

        }else{
            include_once("../../../service/view/nullMessage.php");
        }
    }

    public function check_cookie()
    {
        if( isset($_COOKIE['contents_history']))
        {
            //print_r( $_COOKIE['contents_history']);
            $cookie_string = $_COOKIE['contents_history'];
            parse_str ($cookie_string , $cookie_parse );
            $cookie_contents_number = $cookie_parse['concate'].'_'.$cookie_parse['conno'];

            if( $this->top_category == $cookie_parse['cate'] && $this->now_page == $cookie_parse['page'])
            {
                echo "<script>scroll_from_cookie('$cookie_contents_number');console.log(1)</script>"; //find pre click contents
            }else
            {
                echo "<script>scroll_from_param('$this->now_page');</script>"; //find pre click contents
            }
        }else
        {
            echo "<script>scroll_from_param('$this->now_page');</script>"; //find pre click contents
        }
    }
}
?>