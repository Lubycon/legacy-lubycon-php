<script src="./component/view/chart/amcharts.js" type="text/javascript"></script>
<script src="./component/view/chart/serial.js" type="text/javascript"></script>
<script src="./component/view/chart/lubytheme.js" type="text/javascript"></script>
<script src="./pages/controller/personal_page/dashboard_renderer.js" type="text/javascript" ></script>
<div id="information_inbody">
    <ul id="dashboard_wrap">
        <!--<li class="dash_section" id="creator_month" style="display: none">
            <div class="dash_header">
                <h4>CREATOR OF THE MONTH</h4>
                <i class="fa fa-angle-up toggle_info"></i>
            </div>
            <div class="dash_body" id="creator_month_body">
                <div class="dash_body_sector" id="dash_creator_infobox">
                    <div class="dash_body_sector" id="dash_creator_info">
                        <figure id="dash_creator_info_background"></figure>
                        <div id="dash_creator_info_p">
                            <figure id="dash_creator_pic_frame">
                                <img src="<?=$one_depth?>/ch/img/creator_of_the_month/SsaRu.png" id="creator_pic">
                            </figure>
                            <ul>
                                <li id="dash_creator_name">SsaRu</li>
                                <li id="dash_creator_job">Engineer</li>
                                <li id="dash_creator_location"><i class="fa fa-map-marker"></i><p>Seoul, South korea</p></li>
                            </ul>
                        </div>
                    </div>
                    </div>
                    <div class="dash_body_sector" id="dash_creator_placed">
                        <p class="dash_body_title">Placed</p>
                        <p class="dash_body_content">September, 2016</p>
                    </div>
                    <div class="dash_body_sector" id="dash_creator_interview">
                        <p class="dash_body_title">Interview</p>
                        <p class="dash_body_content">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                        <a href="#">VIEW MORE</a>
                    </div>
        </li>-->
        <li class="dash_section" id="basic_information">
            <div class="dash_header">
                <h4>BASIC INFORMATION</h4>
                <i class="fa fa-angle-down toggle_info selected"></i>
            </div>
            <div class="dash_body">
                <div class="dash_body_sector">
                    <p class="dash_body_title">Job</p>
                    <div class="dash_body_content">
                        <p class="content_text"></p>
                        <p class="content_text" data-value="job"></p>
                    </div>
                </div>
                <div class="dash_body_sector">
                    <p class="dash_body_title">Position</p>
                    <div class="dash_body_content">
                        <p class="content_text"></p>
                        <p class="content_text" data-value="position"></p> 
                    </div>
                </div>
                <div class="dash_body_sector" >
                    <p class="dash_body_title">Location</p>
                    <div class="dash_body_content">
                        <p class="content_text" data-value="city"></p>
                        <p class="content_text" data-value="country"></p>
                    </div>
                </div>
                <div class="dash_body_sector" data-value="language">
                    <p class="dash_body_title">Language</p>
                    <div class="dash_body_content">
                        <p class="content_text" data-value="language1"></p>
                        <p class="content_text" data-value="language2"></p>
                    </div>
                </div>
            </div>
        </li>
        <li class="dash_section" id="history">
            <div class="dash_header">
                <h4>HISTORY</h4>
                <i class="fa fa-angle-down toggle_info selected"></i>
            </div>
            <div class="dash_body">
                <ul class="history_wrap">
                    <!--HISTORY-->
                </ul>
                <aside id="history_desc" class="hidden-mb-ib">
                    <p class="history_desc_list" id="work_desc">
                        <i class="fa fa-circle"></i>
                        <span>Work Experience</span>
                    </p>
                    <p class="history_desc_list" id="edu_desc">
                        <i class="fa fa-circle"></i>
                        <span>Education</span>
                    </p>
                    <p class="history_desc_list" id="award_desc">
                        <i class="fa fa-circle"></i>
                        <span>Awards</span>
                    </p>
                </aside>
            </div>
        </li>
        <li class="dash_section" id="dashboard_graph">
            <div class="dash_header">
                <h4>INSIGHT</h4>
                <i class="fa fa-angle-down toggle_info selected"></i>
            </div>
            <div class="dash_body">
                <div id="total_counts">
                    <div class="dash_body_sector insight_total" id="total_like">
                        <p class="dash_body_title">Total Like</p>
                        <p class="dash_body_content"></p>
                    </div>
                    <div class="dash_body_sector insight_total" id="total_view">
                        <p class="dash_body_title">Total View</p>
                        <p class="dash_body_content"></p>
                    </div>
                    <div class="dash_body_sector insight_total" id="total_upload">
                        <p class="dash_body_title">Total Upload</p>
                        <p class="dash_body_content"></p>
                    </div>
                    <div class="dash_body_sector insight_total" id="total_download">
                        <p class="dash_body_title">Total Download</p>
                        <p class="dash_body_content"></p>
                    </div>
                </div>
                <div id="dash_chart_wrap">
                    <p class="dash_body_title">Last 7 days data</p>
                    <div class="dash_body_sector x2">
                        <div class="chart-boxes">
                            <div class="chart-title">Like</div>
                            <div class="chart-canvas" id="chartdiv1"></div>
                        </div>
                    </div>
                    <div class="dash_body_sector x2">
                        <div class="chart-boxes">
                            <div class="chart-title">View</div>
                            <div class="chart-canvas" id="chartdiv2"></div>
                        </div>
                    </div>
                    <div class="dash_body_sector x2">
                        <div class="chart-boxes">
                            <div class="chart-title">Upload</div>
                            <div class="chart-canvas" id="chartdiv3"></div>
                        </div>
                    </div>
                    <div class="dash_body_sector x2">
                        <div class="chart-boxes">
                            <div class="chart-title">Download</div>
                            <div class="chart-canvas" id="chartdiv4"></div>
                        </div>
                    </div>
                </div>
            </div>
        </li>
        <li class="dash_section" id="contact">
            <div class="dash_header">
                <h4>CONTACT</h4>
                <i class="fa fa-angle-down toggle_info selected"></i>
            </div>
            <div class="dash_body">
                <div class="dash_body_sector x2" id="user-website">
                    <p class="dash_body_title">Website</p>
                    <a href="#" class="dash_body_content"></a>
                </div>
                <div class="dash_body_sector" id="usertime">
                    <p class="dash_body_title">User`s time</p>
                    <div class="clock_wrap" data-value="world">
                        <div class="ampm"></div>
                        <div class="clock"></div>
                    </div>
                    <div class="time_location" id="user_location">
                        <!--user location-->
                    </div>
                </div>
                <div class="dash_body_sector" id="localtime">
                    <p class="dash_body_title">Your Time</p>
                    <div class="clock_wrap" data-value="local">
                        <div class="ampm"></div>
                        <div class="clock"></div>
                    </div>
                    <div class="time_location" id="local_location">
                        <!--my location-->
                    </div>
                </div>
            </div>
        </li>              
    </ul>
</div>