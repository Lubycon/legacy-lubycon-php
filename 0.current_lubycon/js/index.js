﻿/*----------------------------common js----------------------------*/
var windowWidth = $(window).width(),
    windowHeight = $(window).height();
/////////////////////////////////////////////////////////
//      dragging sensor start
/////////////////////////////////////////////////////////
var dragging = false;
$(function(){
    $(window).on("touchmove", function(){
        dragging = true;
    });
    $(window).on("touchstart", function(){
        dragging = false;
    });
});
/////////////////////////////////////////////////////////
//      dragging sensor end
/////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////
//      loading icon start
/////////////////////////////////////////////////////////
$(function(){
    var $loading = $("<div/>",{"id":"loading_icon"}),
    $icon = $("<i/>",{"class":"fa fa-spinner fa-spin"}),
    objectY = (windowHeight*0.5) - 40;
    $(document)
      .ajaxStart(function() {
        $loading.prependTo("body").show(),
        $icon.css("margin-top",objectY).appendTo($loading);
      })
      .ajaxStop(function() {
        $loading.fadeOut(200,function(){
            $loading.remove();
        });
      });
});

/////////////////////////////////////////////////////////
//      loading icon end
/////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////
//      event handler start
/////////////////////////////////////////////////////////
//This function will be canceled the click event when users touch in mobile devices
//So if you want use any function in mobile, This eventHandler must be called to your function//
function eventHandler(event, selector) {
    event.stopPropagation();
    event.preventDefault();
    if (event.type === 'touchend'){
        selector.off('click');
    }
};
/////////////////////////////////////////////////////////
//      event handler end
/////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////
//      gloval navigation button hover event start
//      get parameter change selected nav color
/////////////////////////////////////////////////////////
$(function (){ //gnb hover event
    $('.bigsub').hover(function () {
        $(this).children("ul").stop().fadeIn(300);
    }, function () {
        $(this).children("ul").stop().fadeOut(300);
    });
});

function getUrlParameter(sParam) //get parameter

{
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++) 
    {
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == sParam) 
        {
            return sParameterName[1];
        }
    }
}

var first_param = getUrlParameter('1');
var seceond_param = getUrlParameter('2');
var third_param = getUrlParameter('3');
var four_param = getUrlParameter('4');

$(function () //selcted change
{
    $('#lnb_nav ul').children('#' + third_param).addClass('selected_nav');
    $(".selected_nav").children("a").click(function(){
        return false;//disabled anchor tag
    });
});       
/////////////////////////////////////////////////////////
//      gloval navigation button hover event end
/////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////
//      change language start
/////////////////////////////////////////////////////////
$(function (){
	$('.lang_selected').mouseenter(function(){
		$('.lang_list').stop().slideDown(300);
	});
	$('#lang_select_bt').mouseleave(function(){
		$('.lang_list').stop().slideUp(300);
	});
    $('.lang_list li').click(function(event){
        var selectedLangText = $(this).text();
        $('.lang_selected').text(selectedLangText);
        $('.lang_list').stop().slideUp(300);
        $('.lang_list li').removeClass();
        $(event.target).addClass("selected_language");
        LanguageValue(selectedLangText);
    });
});
function LanguageValue(lang){
    switch(lang){
        case "CHI" : console.log("Chinese"); break;
        case "ENG" : console.log("English"); break;
        case "FRA" : console.log("French"); break;
        case "GER" : console.log("German"); break;
        case "JPN" : console.log("Japanese"); break;
        case "KOR" : console.log("Korean"); break;
        case "RUS" : console.log("Russian"); break;
        case "SPA" : console.log("Spanish"); break;
        default : return; break;
    }
}
/////////////////////////////////////////////////////////
//      change language end
/////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////
//      after signin child hover show and hide start
/////////////////////////////////////////////////////////
$(function(){
    var $personalMenu = $("#after_signin"),
    $menuList = $personalMenu.find("ul");
	$personalMenu.click(function (){
        var $this = $(this);
        if($this.hasClass("opened")){
            $this.removeClass("opened");
            $menuList.stop().fadeOut(200);
            $("html").off("click");
        }
        else{
            $this.addClass("opened");
            $menuList.stop().fadeIn(200);
            $("html").on("click", function (event) {
                var $this = $(event.target);
                $this.hideAnywhere($this,$personalMenu,$menuList,{
                    a:"#user_id",
                    b:"#display_user",
                    c:"#accountImg"
                });
            });
        }
	});
});
/////////////////////////////////////////////////////////
//      after signin child hover show and hide end
/////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////
//      add contents bt popup event start
/////////////////////////////////////////////////////////
$(function () { //add contents button start
    var $editorWindow = $(".editor_popup"),
    $contents = $editorWindow.find("li"),
    $background = $(".dark_overlay");
    $('#addcontent_bt').click(function () {
       $background.stop().fadeIn(100);
        $editorWindow.css("display","block").attr("class","editor_popup fadeInDown animated");
    });

    $contents.hover(function () {
        $(this).children('a').children('i').css({ "color": "#fff", "background": "#48cfad" })
    }, function () {
        $(this).children('a').children('i').css({ "color": "#383838", "background": "#fff" })
    });

    $('.closeButton , .dark_overlay , .cancel_bt').click(function () {
        $editorWindow.attr("class","editor_popup fadeOutUp animated")
        setTimeout(function(){$editorWindow.hide();},500);
        $background.stop().fadeOut(200);
        $('#embed_popup').stop().fadeOut(150);
    });
});
/////////////////////////////////////////////////////////
//      add contents bt popup event end
/////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////
//      main search bar input reset start
/////////////////////////////////////////////////////////
$(function () { //search box click value reset start
    var search_box = $('#main_search_text'),
    search_box2 = $('#sub_search_text'),
    search_bt = $('#main_search_btn'),
    search_bt2 = $('#sub_search_btn');

    //
    search_box.on('keypress', function(event) {
        if(event.which == 13) {
            search_bt.click();
            console.log("Searching....")
        }
    }).focus(function(){
        if (search_box.val() == 'Enter The Keyword') {
            search_box.val('');
        }
    }).blur(function(){
        if (search_box.val() == '') {
            search_box.val('Enter The Keyword');
        }
    });

    search_box2.on('keypress', function(e) {
        console.log("keypress_true");
        if(e.which == 13) {
            console.log("if true");
            search_bt.click();
        };
    }).click(function(){
        console.log("clicked");
    }).focus(function(){
        if(search_box2.val()=='Enter the Keyword'){
            search_box2.val('')
            $("#sub_search_bar").stop().animate({width:350},200);
        }
    }).blur(function(){
        if(search_box2.val()==''){
            search_box2.val('Enter the Keyword');
            $("#sub_search_bar").stop().animate({width:295},200);
        }
    });
});//search box click value reset end

/////////////////////////////////////////////////////////
//      main search bar input reset end
/////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////
//      main index contents viewmore start(mobile)
/////////////////////////////////////////////////////////
$(function(){
    if($(window).width() < 1025){//It will be activate in only mobile
        $(".mb-view_more").on("click touchend",function(event){
            eventHandler(event,$(this));
            if(!dragging){
                var id = $(this).attr("id"),
                hostURL = location.host,
                url = "";
                switch(id){
                    case "mb-artwork_bt" : 
                        url = 'http://' + hostURL + '/Lubycon_Website/0.current_lubycon/index.php?1=contents&2=contents_page&3=artwork';
                        location.href = url;
                    break;
                    case "mb-vector_bt" :
                        url = 'http://' + hostURL + '/Lubycon_Website/0.current_lubycon/index.php?1=contents&2=contents_page&3=vector';
                        location.href = url;
                    break;
                    case "mb-3d_bt" :
                        url = 'http://' + hostURL + '/Lubycon_Website/0.current_lubycon/index.php?1=contents&2=contents_page&3=3d';
                        location.href = url;
                    break;
                    default: return; break;
                }
            }
            else if(dragging){
                return;
            } 
        });
    }else{
        return;
    }
});
/////////////////////////////////////////////////////////
//      main index contents viewmore end(mobile)
/////////////////////////////////////////////////////////
/*----------------------------common js----------------------------*/
/*----------------------------index page slider----------------------------*/
/////////////////////////////////////////////////////////
//      index page slide switch start
/////////////////////////////////////////////////////////

$(function()  //slider change 
{
	$('#artwork_bt').click(function()
	{
		$('#slider1').stop().fadeIn(150);
		$('#slider2').hide();
		$('#slider3').hide();
	});
	$('#vector_bt').click(function()
	{
		$('#slider1').hide();
		$('#slider2').stop().fadeIn(150);
		$('#slider3').hide();
	});
	$('#3d_bt').click(function()
	{
		$('#slider1').hide();
		$('#slider2').hide();
		$('#slider3').stop().fadeIn(150);
	})
	$('.slider_item ul li').hover(function ()
	{
	    $(this).stop().animate({opacity:0.3},100);
	}, function ()
	{
	    $(this).stop().animate({ opacity: 1 },100);
	});
});
/////////////////////////////////////////////////////////
//      index page slide switch end
/////////////////////////////////////////////////////////


/////////////////////////////////////////////////////////
//      index page triple bt event start
/////////////////////////////////////////////////////////
$(function(){
	$('.la_bt').on({
		mouseenter : function(){
			$('.la_bt').removeClass('over');
			$(this).addClass('over');
		},
		mouseleave: function (){
		    $('.la_bt').removeClass('over');
		    $(this).addClass('out');
		},
		click: function (){
		    $('.la_bt').removeClass('clicked');
		    $(this).addClass('clicked');
		}
	});
});
/////////////////////////////////////////////////////////
//      index page triple bt event end
/////////////////////////////////////////////////////////
/*----------------------------index page slider end----------------------------*/
/*----------------------------contents page----------------------------*/
/////////////////////////////////////////////////////////
//      contents card hover overlay view start
/////////////////////////////////////////////////////////
$(function (){
    if(windowWidth >= 1025){
        $(document).on({
            mouseenter: function() {
                $(this).children('.contents_overlay').stop().fadeIn(300);
                $(this).find(".contents_title").css({
                    "text-decoration":"underline",
                    "color":"#48cfad"
                });
            },
            mouseleave: function() {
                $(this).children('.contents_overlay').stop().fadeOut(300);
                $(this).find(".contents_title").css({
                    "text-decoration":"none",
                    "color":"#444444"
                });
            }
        }, '.contents_card');
    }
    else{
        return;
    }
});
/////////////////////////////////////////////////////////
//      contents card hover overlay view end
/////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////
//      contents view con_left hovering start
/////////////////////////////////////////////////////////
$(function(){
    if(($("#contents_main").length != 0) && windowWidth >= 1025){
        floating_bt_action();
    }
    else{
        return;
    }
});
function floating_bt_action(){
    $("#contents_main").hover(function(){
        $("#floating_bt").stop().fadeIn(200);
        $(document).scroll(function(){
            if($("#floating_bt").offset().top > $("#comment_box").offset().top - 50){
                $("#floating_bt").stop().fadeOut(200);
            }
            else{
                $("#floating_bt").stop().fadeIn(200);
                return;
            }
        });
    },function(){
        $("#floating_bt").stop().fadeOut(200);
        $(document).scroll(function(){
            $("#floating_bt").stop().fadeOut(200);
            return;
        });
    });
}
/////////////////////////////////////////////////////////
//      contents view con_left hovering end
/////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////
//      comment write box auto height start
/////////////////////////////////////////////////////////
function Expander() {
    this.start = function () {
        $("#comment_text").keydown(function(event) {
            this.style.height = 0;
            var newHeight = this.scrollHeight + 5;
            
            if( this.scrollHeight >= this.clientHeight ){
                newHeight += 5;
                this.style.height= newHeight + 'px';
            }//if end
        });//keydown end
    }//function end
}//expander function end

$(function() {
    window.app = new Expander();
    window.app.start();
});
/////////////////////////////////////////////////////////
//      comment write box auto height end
/////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////
//      contents view file_info toggle start
/////////////////////////////////////////////////////////
$(function () {
    var toggle_count = 0;
    $(document).on('click', '#info_header', function ()
    {
        switch (toggle_count) {
            case 0:
                file_info_slidedown();
                toggle_count = 1;
                break;

            case 1:
                file_info_slideup();
                toggle_count = 0;
                break;
        };
    });
});

function file_info_slidedown() {
    $("#files").stop().slideDown(300);
    $("#info_toggle").attr('class', 'fa fa-angle-up');
    toggle_count = 1;
};
function file_info_slideup() {
    $("#files").stop().slideUp(300);
    $("#info_toggle").attr('class', 'fa fa-angle-down');
    toggle_count = 0;
};
/////////////////////////////////////////////////////////
//      contents view file_info toggle end
/////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////
//      contents view descript box toggle start
/////////////////////////////////////////////////////////
$(function(){
    var $button = $("#view_descript"),
    $object = $button.next("#descript_box");
    $button.click(function(){
        var $this = $(this);
        if($button.hasClass("opened")){
            $object.stop().fadeOut(200);
            $button.removeClass("opened");
        }
        else{
            $object.stop().fadeIn(200);
            $button.addClass("opened");
            $("html").on("click", function (event) {
                var $this = $(event.target);
                $this.hideAnywhere($this,$button,$object,{
                    a:"#view_descript",
                    b:"#descript_box"
                });
            });
        }
    })
});
/////////////////////////////////////////////////////////
//      contents view descript box toggle end
/////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////
//      community mainboard start
/////////////////////////////////////////////////////////
$(window).on("load resize",function(){
    if($("#main_board").length != 0){
        var wholeList = $(".table_list"),
        list = $(".table_list_inner"),
        userimg = $(".table_user_img"),
        number = $(".table_number_wrap"),            
        count = $(".table_counts"),
        subject = $(".table_subject");
        var list_padding = list.innerWidth() - list.width();
        var resWidth;
        if(windowWidth >= 1025){
            resWidth = (wholeList.width() - list_padding - userimg.width() - number.outerWidth(true) - count.width() - 100).toString() + "px";
        }
        else if(windowWidth < 1025){
            resWidth = (wholeList.width() - list_padding - userimg.width() - 50).toString() + "px";
        }
        subject.css({ "max-width" : resWidth });
        return;
    }
    else{
        return;
    }
});
/////////////////////////////////////////////////////////
//      community mainboard end
/////////////////////////////////////////////////////////
/*----------------------------contents page----------------------------*/
/*----------------------------start personal page----------------------------*/
/////////////////////////////////////////////////////////
//      personal page subnav hover and ajax
/////////////////////////////////////////////////////////
$(function () {
    $('document').ready(function () {
        if ($('.personal').attr('class') == 'personal con_main') {
            var id = third_param;
            $.ajax({
                type: "POST",
                url: "php/ajax/subnav_check.php", //이페이지에서 중복체크를 한다
                data: 'id=' + id,//test.asp에 id 값을 보낸다
                cache: false,
                success: function (data) {
                    //$('#bodyer').hide().append(data).fadeIn(300); //해당 내용을 보여준다
                    $('#contents_box').html('');
                    $('#contents_box').append(data);
                    if ($('document').find(".subnav_li")) {
                        var urltxt = "#" + third_param.toString();
                        $(".subnav_li").attr("class", "subnav_li");
                        $(urltxt).addClass("selected_subnav");
                    }
                }
            });
        }//if end
    });

    $("#subnav li").click(function (){       
        var id = $(this).attr('id');
        var hostURL = location.host;
        location.href = 'http://' + hostURL + '/Lubycon_Website/0.current_lubycon/index.php?1=personal_page&2=personal_page&3=' + id;
    });
});
/////////////////////////////////////////////////////////
//      personal page subnav hover and ajax
/////////////////////////////////////////////////////////

/*----------------------------personal page----------------------------*/
/*----------------------------waiting for resisting start----------------------------*/

/////////////////////////////////////////////////////////
//      waiting for resisting animate
/////////////////////////////////////////////////////////
$(function(){
    $("#thanks").animate({opacity:1},500);
    $("#thanks").queue(function(){
        $("#thanks2").animate({opacity:1},500);//
        $("#thanks2").queue(function(){
            $("#circle").animate({opacity:1},800); 
        });
    }); 
});

$(function(){
    $('#circle').hover(
        function (){
            $(this).stop().animate({opacity:0.7},200);
            $('#gotomain').stop().animate({opacity:1},500);
        },
        function (){
            stop();
            $(this).stop().animate({opacity:1},200);
            $('#gotomain').stop().animate({opacity:0},500);
        }
    );
});
/////////////////////////////////////////////////////////
//      waiting for resisting animate
/////////////////////////////////////////////////////////

/*----------------------------waiting for resisting end----------------------------*/




/*----------------------------waiting for resisting end----------------------------*/
/*----------------------------followers start--------------------------*/
$(function(){
    var toggle_count=0;//from DB
    $(document).on('click','.follow_card_bt', function(){
        switch(toggle_count){
            case 0:
                $(this).css("background","#333");
                $(this).children().attr("class","fa fa-user-times");
                toggle_count=1;
                console.log(toggle_count);
            break;

            case 1:
                $(this).css("background","#48cfad");
                $(this).children().attr("class","fa fa-user-plus");
                toggle_count=0;
                console.log(toggle_count);
            break;
        };
    });
});
/*----------------------------followers end----------------------------*/
/*----------------------------pager interaction start--------------------------*/
$(function(){
    var page_li = $(".page_list li");

    $(".ten_page").hover(function(){
        $(this).stop().animate({opacity:0.6},300);
    },function(){
        $(this).stop().animate({opacity:1},300);
    });
    
    $(".one_page").hover(function(){
        $(this).stop().animate({opacity:0.6},300);
    },function(){
        $(this).stop().animate({opacity:1},300);
    });


    page_li.hover(function(){
        $(this).stop().animate({opacity:0.6},300);
    },function(){
        $(this).stop().animate({opacity:1},300);
    })
    
    page_li.click(function(){
        page_li.removeClass();
        $(this).addClass("selected_pager");
    })

});
/*----------------------------pager interaction end--------------------------*/
/*--------------------my info setting in creator_page toggle start------------*/
$(function(){
    if($("#myinfo_setting").length != 0){
        var $button = $("#myinfo_setting"),
        $menu = $button.next("#myinfo_menu_list");
        $button.click(function(){
            var $this = $(this);
            if($this.hasClass("opened")){
                $menu.stop().fadeOut(200);
                $this.removeClass("opened");
            }
            else{
                $this.addClass("opened");
                $menu.stop().fadeIn(200);
                $("html").on("click", function (event) {
                    var $this = $(event.target);
                    $this.hideAnywhere($this,$button,$menu,{
                        a:"#myinfo_setting"
                    });
                });
            };
        });
    };
});
/*--------------------my info setting in creator_page toggle end----------------------*/
/*----------------------------creator card menu toggle start--------------------------*/
$(function(){
    $(".creator_menu").each(function(){
        var $this = $(this),
        $button = $(".creator_menu"),
        $menu = $this.children(".creator_menu_list");
        $this.click(function (event){
            event = event || window.event//for IE
            if($this.hasClass("opened")){
                $this.removeClass("opened");
                $menu.stop().fadeOut(200);
                console.log("a");
            }
            else{
                console.log("b");
                $this.addClass("opened");
                $menu.stop().fadeIn(200);
                $("html").on("click", function (event) {
                    var $this = $(event.target);
                    $this.hideAnywhere($this,$button,$menu,{
                        a:".creator_menu_icon"
                    });
                });
            }
        });
    });
});

/*----------------------------creator card menu toggle end--------------------------*/