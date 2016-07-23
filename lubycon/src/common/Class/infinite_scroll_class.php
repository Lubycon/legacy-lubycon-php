<?php
class infinite_scroll extends json_control
{
    // from ajax post
    private $Loginuser_code = null;

    private $cardType;
    private $page;
    private $topCate;
    private $filter;
    private $sort;
    private $searchFilter;
    private $searchValue;
    private $nowPage;
    private $targetPage;

    // page limit option
    private $pageStartPoint;
    private $page_boundary;

    // content and forum allow top category list
    private $allow_array_list;
    private $allow_array_content = ['all','artwork','vector','threed'];
    private $allow_array_community = ['all','forum','tutorial','qaa'];

    private $searchFilterQuery;
    private $midCateQuery;
    private $licenseQuery;
    private $jobQuery;
    private $continentQuery;
    private $myBookmarkQuery;
    private $myContentQuery;


    public $select_query;
    public $from_query;
    public $where_query;
    public $order_query;
    public $limit_query;

    public $bind_data=array();

    public $ccDecode;

    /*
    //Client -> Server
    {
        url: "페이지 URL",
        type: enum, (contents, creator, community, comment)
        topCate: int,
        sort: 1,
        filter: {
            midCate: 1,
            license: 1,
            continent: 1,
            job: 1,
            search: 1,
            targetUser: 1,
            bookmark: true
        },
        searchValue: 1,
        nowPage: 1,
        targetPage: 1
    };

    //Server -> Client
    {
        header: {
            responseCode:{
                code: "string",
                devMsg: "서버에서 에러난 메세지 그대로 보내주셈",
                message: "서버 코드에 관련된 설명"
            }
        },
        result:{
            //DATA
        }
    };
    */
	public function __construct($postData,$Loginuser_code)
    {
        $this->cardType = $postData->type;
        $isset_topCate = isset($postData->topCate);
        if($isset_topCate)
        {
            $this->json_decode($this->cardType."_top_category","../../../../data/top_category.json");
            $this->topCateDecode = $this->json_decode_code;
        }
        $this->json_decode('ccCode',"../../../../data/ccCode.json");
        $this->ccDecode = $this->json_decode_code;
        $this->json_decode('job',"../../../../data/job.json");
        $this->jobDecode = $this->json_decode_code;
        $this->json_decode('country',"../../../../data/country.json");
        $this->countryDecode = $this->json_decode_code;
		// default arg is page kind
        $this->Loginuser_code = $Loginuser_code;

        $this->url = $postData->url;
        $this->topCateCode = $isset_topCate ? $postData->topCate : null;
        $this->topCateName = $isset_topCate ? $this->topCateDecode[$this->topCateCode]['name'] : null;
        $this->filter = $postData->filter;
        $this->sort = $postData->sort;
        $this->searchValue = $postData->searchValue;
        $this->nowPage = $postData->nowPage;
        $this->targetPage = $postData->targetPage;

        //optcion to call each
        $this->page_boundary = 30;
        $this->pageStartPoint = ($this->targetPage-1) * $this->page_boundary;

        //query set
        $this->searchQuery = $this->searchValue !== null ? $this->filter->search." like '%".$this->searchValue."%'" : $this->filter->search = null ;
        $this->midCateQuery = $this->filter->midCate > 0 ? $this->filter->midCate.' IN (a.`midCategoryCode0`,a.`midCategoryCode1`,a.`midCategoryCode2`)' : null;
        $this->licenseQuery = $this->filter->license > 0 ? 'a.`ccLicense` = '.($this->filter->license) : null;
        $this->jobQuery = $this->filter->license > 0 ? 'ui.`jobCode` = '.($this->filter->job) : null; 
        $this->continentQuery = $this->filter->license > 0 ? 'a.`continent` = '.($this->filter->continent) : null;
        $this->bookmarkQuery = isset($this->filter->bookmark) ? 'b.`bookmarkActionUserCode` = '.$this->loginuser_code : null;
        $this->targetUserQuery = isset($this->filter->targetUser) ? 'c.`userCode` = '.$this->filter->targetUser : null;


         //set allow array form page kinds
        if($this->cardType == 'content')
        {
            $this->allow_array_list = $this->allow_array_content;
            $this->validateCategory();
        }
        else if($this->cardType == 'community')
        {
            $this->allow_array_list = $this->allow_array_community;
            $this->validateCategory();
        }
	}

    private function validateCategory() //check top category form allow array
    {
        if( !in_array($this->topCateName , $this->allow_array_list) )
        {
            echo 'Unknown top category name errorCode:0001';
            die();
        }
    }

    public function initQuery() //set default query option
    {
        switch($this->cardType)
        {
            case 'content' :
                $this->select_query = 
                    "
                    SELECT SQL_CALC_FOUND_ROWS 
                    a.`boardCode`,a.`userCode`,a.`topCategoryCode`,a.`contentTitle`,a.`userDirectory`,a.`ccLicense`,a.`downloadCount`,a.`commentCount`,
                    a.`viewCount`,a.`likeCount` , a.`midCategoryCode0`, a.`midCategoryCode1`, a.`midCategoryCode2` ,a.`contentStatus` , ub.`nick` 
                    ";
                if($this->topCateName === 'all')
                {
                    $this->from_query = 
                    "
                    FROM 
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
                    ) AS a 
                    LEFT JOIN lubyconuser.`userbasic` AS as ub
                    ON a.`userCode` = ub.`userCode` 
                    ";
                }else
                {
                    $this->from_query =
                    "
                        from 
                        (
                        SELECT * FROM lubyconboard.`$this->topCateName`                 
                        LEFT JOIN lubyconboard.`$this->topCateName"."midcategory`
                        USING (`boardCode`)
                        ) as a
                        left join lubyconuser.`userbasic` as ub
                        ON a.`userCode` = ub.`userCode`
                    ";
                }

                $this->where_query = " WHERE a.`contentStatus` = 'normal' AND";

                if($this->Loginuser_code != null)
                {
                    $this->select_query .= " ,b.`bookmarkActionUserCode` ";
                    $this->from_query .= "LEFT JOIN lubyconboard.`contentsbookmark` AS b ON a.`boardCode` = b.`boardCode` AND a.`topCategoryCode` = b.`topCategoryCode` AND b.`bookmarkActionUserCode` = $this->Loginuser_code ";
                }

            break;
            case 'community' : 
                $this->select_query = "SELECT * ";
                $this->from_query = 
                "
                FROM lubyconboard.`$this->topCateName` as a 
                LEFT join lubyconuser.`userbasic` as ub
                USING (`userCode`)
                LEFT join lubyconuser.`userinfo` as ui
                USING (`userCode`)
                ";
                $this->where_query = " WHERE a.`contentStatus` = 'normal' AND";

            break;
            case 'creator' : 
                $this->select_query =
                "SELECT  `userbasic`.`userCode` , `nick` , `jobCode` , `boardCode` , `city` , `countryCode` , `userDirectory`";
                $this->from_query=
                "
                FROM lubyconboard.`artwork` as a
                INNER join lubyconuser.`userbasic` as ub
                INNER join lubyconuser.`userinfo` as ui
                ON a.`userCode` = ub.`userCode` 
                and ub.`userCode` = ui.`userCode` 
                ";
                $this->sort = 5 ; //temp
            break;
            case 'comment' : break;
            default : die( 'initQuery switcher error' ); break;
        }
        $this->limit_query = " limit $this->pageStartPoint , $this->page_boundary";
    }

    public function setOption() //set query from user needs
    {
        foreach( $this->filter as $key => $value )
        {
            if( $this->{$key."Query"} !== null )
            {

                echo $value."is worng";

                $addQuery = $this->{$key."Query"};
                $this->where_query .= " $addQuery and ";
            }
        }
        $this->where_query = substr($this->where_query, 0, -4);//delete last and string


        switch($this->sort)
        {
            case 0 : $this->order_query = " ORDER BY a.`viewCount` DESC "; break;
            case 1 : $this->order_query = " ORDER BY a.`contentDate` DESC "; break;
            case 2 : $this->order_query = " ORDER BY a.`likeCount` DESC "; break;
            case 3 : $this->order_query = " ORDER BY a.`downloadCount` DESC "; break;
            case 4 : $this->order_query = " ORDER BY a.`commentCount` DESC "; break;
            case 5 : $this->order_query = " ORDER BY `userCode` DESC "; break;//temp
            default : $this->order_query = " ORDER BY a.`contentDate` DESC "; break;
        }


        /*
        echo $this->select_query;
        echo $this->from_query;
        echo $this->where_query;
        echo $this->order_query;
        echo $this->limit_query;
        */
    }

    public function bindResult($query_result)
    {
        switch($this->cardType)
        {
            case 'content' :
                while( $row = mysqli_fetch_assoc($query_result['contents']) )
                {
                    $bookmark_check = isset($row['bookmarkActionUserCode']) ? 'true' : 'false';
                    $this->json_search($this->ccDecode,'name','ccLicense',$row['ccLicense']);
                    $license_check = $this->search_key;

                    $this->bind_data[] = array(
                        'code' => $row['boardCode'],
                        'title' => $row['contentTitle'],
                        'category' => $row['topCategoryCode'],
                        'thumbnail' => $row['userDirectory'].'/thumbnail/thumbnail.jpg',
                        'license' => $license_check,
                        'bookmark' => $bookmark_check,
                        'userData' => array(
                            'code' => $row['userCode'],
                            'name' => $row['nick'],
                            'profile' => "../../../../Lubycon_Contents/user/".$row['userCode']."/profile.jpg",
                        ),
                        'contentCount' => array(
                            'view' => $row['viewCount'],
                            'comment' => $row['commentCount'],
                            'like' => $row['likeCount'],
                        )
                    );
                }
            break;
            case 'community' : 
                while( $row = mysqli_fetch_assoc($query_result['contents']) )
                {
                    $this->bind_data[] = array(
                        'content' => array(
                            'code' => $row['boardCode'],
                            'title' => $row['contentTitle'],
                            'comment' => $row['commentCount'],
                            'like' => $row['likeCount'],
                            'view' => $row['viewCount'],
                            'date' => $row['contentDate']
                        ),
                        'user' => array(
                            'code' => $row['userCode'],
                            'name' => $row['nick'],
                            'profile' => $row['profileImg']
                        )
                    );
                }
            break;
            case 'creator' : 
                while( $row = mysqli_fetch_assoc($query_result['bestCreator']) )
                {
                    $job_origin_select = $this->jobDecode[$row['jobCode']]['name'];
                    $country_origin_select = $this->countryDecode[$row['countryCode']]['name'];
                    $this->bind_data[] = array(
                        'code' => $row['userCode'],
                        'profile' => "../../../../Lubycon_Contents/user/".$row['userCode']."/profile.jpg",
                        'name' => $row['nick'],
                        'job' => $job_origin_select,
                        'countryCode' => $row['countryCode'],
                        'country' => $country_origin_select,
                        'city' => $row['city'],
                        'randCount' => rand(200,1500),
                        'contentsCount' => '0',
                        'contents' => array(
                            array(
                                'id' => $row['boardCode'],
                                'img' => $row['userDirectory'].'/thumbnail/thumbnail.jpg'
                            ),
                            array(
                                'id' => $row['boardCode'],
                                'img' => $row['userDirectory'].'/thumbnail/thumbnail.jpg'
                            ),
                            array(
                                'id' => $row['boardCode'],
                                'img' => $row['userDirectory'].'/thumbnail/thumbnail.jpg'
                            )
                        ),
                        'bestCreator' => true
                    );
                }
                while( $row = mysqli_fetch_assoc($query_result['contents']) )
                {
                    $job_origin_select = $this->jobDecode[$row['jobCode']]['name'];
                    $country_origin_select = $this->countryDecode[$row['countryCode']]['name'];
                    $this->bind_data[] = array(
                        'code' => $row['userCode'],
                        'profile' => "../../../../Lubycon_Contents/user/".$row['userCode']."/profile.jpg",
                        'name' => $row['nick'],
                        'job' => $job_origin_select,
                        'countryCode' => $row['countryCode'],
                        'country' => $country_origin_select,
                        'city' => $row['city'],
                        'randCount' => rand(200,1500),
                        'contentsCount' => '0',
                        'contents' => array(
                            array(
                                'id' => $row['boardCode'],
                                'img' => $row['userDirectory'].'/thumbnail/thumbnail.jpg'
                            ),
                            array(
                                'id' => $row['boardCode'],
                                'img' => $row['userDirectory'].'/thumbnail/thumbnail.jpg'
                            ),
                            array(
                                'id' => $row['boardCode'],
                                'img' => $row['userDirectory'].'/thumbnail/thumbnail.jpg'
                            )
                        ),
                        'bestCreator' => false
                    );
                }
            break;
            case 'comment' : break;
            default : die( 'initQuery switcher error' ); break;
        }
    }


    /* need change logic to model
    public function set_query($query_user_code)
    {
        if( $this->top_category == 'all' )
        {
            $this->query = "
            SELECT SQL_CALC_FOUND_ROWS 
            a.`boardCode`,a.`userCode`,a.`topCategoryCode`,a.`contentTitle`,a.`userDirectory`,a.`ccLicense`,a.`downloadCount`,a.`commentCount`,a.`viewCount`,a.`likeCount`, c.`nick`, a.`midCategoryCode0`, a.`midCategoryCode1`, a.`midCategoryCode2` ,a.`contentStatus`";
            if($query_user_code != '')
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
            if($query_user_code != '')
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
            if($query_user_code != '')
            {$this->query .= " ,b.`bookmarkActionUserCode` ";}
            $this->query .= 
            " from 
                (
                SELECT * FROM lubyconboard.`$this->top_category`                 
                LEFT JOIN lubyconboard.`$this->top_category"."midcategory`
                USING (`boardCode`)
                ) as a
            ";
            if( $query_user_code != '' )
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

    /*
    public function count_page($db_result) // count all page function
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

                //page load
                if($i == $this->page_limit && !$ajax_boolean)
                {
                    echo "<div class='scroll_checker page_bottom_$this->start_page'></div>";
                    $i = 1;
                    $this->start_page++;
                }else
                {
                    $i++;
                }
                //page load
            }   
            //ajax
            if($ajax_boolean)
            {
                echo "<div class='scroll_checker page_bottom_$this->target_page'></div>";
            }
            //ajax

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
            echo "<script>scroll_from_param('private $now_page');</script>"; //find pre click contents
        }
    }
    */
}
?>