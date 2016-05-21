﻿//This function will be canceled the click event when users touch in mobile devices
//So if you want use any function in mobile, This eventHandler must be called to your function//
function eventHandler(event, selector) {
    event.stopPropagation();
    event.preventDefault();
    if (event.type === 'touchend'){
        selector.off('click');
    }
};
/////////////////////////////////////////////////////////////////////////////////////////////////parameter
var CATE_PARAM = getUrlParameter('cate'); // GLOBAL
var MID_CATE_PARAM = getUrlParameter('mid_cate'); // GLOBAL
var CONNUM_PARAM = getUrlParameter('conno'); // GLOBAL
var BNO_PARAM = getUrlParameter('bno'); //GLOBAL
var PAGE_PARAM = getUrlParameter('page'); //GLOBAL

function getUrlParameter(sParam){
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++){
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == sParam){
            return sParameterName[1];
        }
    }
}

function replaceUrlParameter(sParam,value){
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++){
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == sParam) {
            history.pushState(null, "", location.pathname + '?' + sPageURL.replace(sParameterName[0] + '=' + sParameterName[1], sParameterName[0] + '=' + value));
        }
    }
}
/////////////////////////////////////////////////////////////////////////////////////////////////parameter
$(function (){ //gnb hover event
    $('.bigsub').hover(function () {
        $(this).children("ul").stop().fadeIn(300);
    }, function () {
        $(this).children("ul").stop().fadeOut(300);
    });
});

$(function (){
    $('.lnb_nav ul').children('#' + CATE_PARAM).addClass('selected');
    $('#subnav ul').children('#' + CATE_PARAM).addClass('selected');
    $(".selected").children("a").click(function(){
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
    $("#lang_select_bt").hover(function(){
        $(this).find(".lang_list").stop().slideDown(300);
    },function(){
        $(this).find(".lang_list").stop().slideUp(300);
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
	$personalMenu.on("click",toggle.single).on("click",personalMenuToggle);
    function personalMenuToggle(){
        var $this = $(this);
        if($this.hasClass("selected")){
            $menuList.stop().fadeIn(200);
            $menuList.hideAnywhere($this);
        }
        else{
            $menuList.stop().fadeOut(200);
            $menuList.off("hideAnywhere");
        }
    }
});
/////////////////////////////////////////////////////////
//      after signin child hover show and hide end
/////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////
//      add contents bt popup event start
/////////////////////////////////////////////////////////
$(function () { //add contents button start
    var $editorModal = $(".editor_popup.modal"),
    $darkOverlay = $(".dark_overlay");
    $('#addcontent_bt').click(function () {
        $darkOverlay.stop().fadeIn(100);
        $editorModal.css("display","block").attr("class","editor_popup modal fadeInDown animated");
    });
});
/////////////////////////////////////////////////////////
//      add contents bt popup event end
/////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////
//     search bar input reset start
/////////////////////////////////////////////////////////
$(function () { //search box click value reset start
    var searchBar = $(document).find(".search-bar"),
    searchBarInput = searchBar.find(".search-bar-text");
    searchBar.on("keypress",enterPressed);
    searchBarInput.on("focus",onFocus).on("blur",onBlur);
    
    function enterPressed(event){
        console.log(true);
        if(event.which === 13) $(this).find(".search_btn").trigger("click");
    }
    function onFocus(){
        console.log($(this).val());
        if($(this).val() !== "") $(this).val("");
    }
    function onBlur(){
        if($(this).val() === "") $(this).val("Enter the keyword");
    }

});
/////////////////////////////////////////////////////////
//     search bar input reset end
/////////////////////////////////////////////////////////
/*----------------------------common js----------------------------*/
/*----------------------------index page slider----------------------------*/
/////////////////////////////////////////////////////////
//      index page slide switch start
/////////////////////////////////////////////////////////
$(function(){
    $('.la_bt').on("click", toggle.group);
    $(".slide-radio").on("change",slideChecker);

    function slideChecker(){
        var $this = $("." + $(this).attr("class") + ":checked"),
        data = $this.data("value"),
        $sliders = $("#slide_section > .slider-wrapper");
        $target = $("#slider" + data);

        $sliders.hide();
        $target.stop().fadeIn(150);
    }
});
/////////////////////////////////////////////////////////
//      index page slide switch end
/////////////////////////////////////////////////////////
/*----------------------------index page slider end----------------------------*/
/*----------------------------contents page----------------------------*/
/////////////////////////////////////////////////////////
//      contents card hover overlay view start
/////////////////////////////////////////////////////////
$(function (){
    if($(window).width() >= 1025){
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
    var button = $(".floating_bt"),
    contentsMain = $("#contents_main");
    if(($("#contents_main").length !== 0) && $(window).width() >= 1025){
        $(document).on("scroll",floatingButtonScroll);
    };

    function floatingButtonScroll(){
        if(floatingButtonChecker()) contentsMain.on("mousemove",floatingButtonShow).on("mouseleave",floatingButtonHide);
        else {
            button.fadeOut(200);
            contentsMain.off("mousemove",floatingButtonShow).off("mouseleave",floatingButtonHide);
        }
    }
    function floatingButtonShow(){
        button.fadeIn(200);
    }
    function floatingButtonHide(){
        button.fadeOut(200);
    }
    function floatingButtonChecker(){
        var contentTitleVisible = $("#contents_info_wrap").css("display") === "none" && $(document).scrollTop() !== 0,
        scrollEnd = button.offset().top < $("#comment_box").offset().top - 50;

        if(contentTitleVisible && scrollEnd ) return true;
        else return false;
    }
});
/////////////////////////////////////////////////////////
//      contents view con_left hovering end
/////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////
//      comment write box auto height start
/////////////////////////////////////////////////////////
$(function() {
    window.app = new InputExpander("#comment_text");
    window.app.start();
});
/////////////////////////////////////////////////////////
//      comment write box auto height end
/////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////
//      contents view title box start
/////////////////////////////////////////////////////////
$(function (){
    var $this = $(document).find("#contents_info_wrap"),
    notMobile = $(window).width() >= 1024;
    $(document).scroll(function(event){
        var scrollTop = $(document).scrollTop();
        if(notMobile && scrollTop >= 50){
            $this.fadeOut(400);
        }
        else if(notMobile && scrollTop < 50){
            $this.stop().fadeIn(400);
        }
    })
})
/////////////////////////////////////////////////////////
//      contents view title box end
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
        if($(window).width() >= 1025){
            resWidth = (wholeList.width() - list_padding - userimg.width() - number.outerWidth(true) - count.width() - 100).toString() + "px";
        }
        else if($(window).width() < 1025){
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
/*--------------------my info setting in creator_page toggle start------------*/
$(function(){
    if($("#myinfo_setting").length != 0){
        var $button = $("#myinfo_setting"),
        $menu = $button.next("#myinfo_menu_list");
        $button.on("click",toggle.single).on("click",myinfoToggle);

        function myinfoToggle(){
            var $this = $(this);
            if($this.hasClass("selected")){
                $menu.stop().fadeIn(200);
                $menu.hideAnywhere($this);
            }else{
                $menu.stop().fadeOut(200);
                $menu.off("hideAnywhere");
            }
        }
    };
});
/*--------------------my info setting in creator_page toggle end----------------------*/
/*----------------------------creator card menu toggle start--------------------------*/
$(function(){
    $(".creator_menu").each(function(){
        var $this = $(this),
        $button = $(".creator_menu"),
        $menu = $this.children(".creator_menu_list");
        $this.on("click",toggle.single).on("click",creatorMenuToggle);

        function creatorMenuToggle(){
            if($this.hasClass("selected")){
                $menu.stop().fadeIn(200);
                $menu.hideAnywhere($this);
            }
            else{
                $menu.stop().fadeOut(200);
                $menu.off("hideAnywhere");
            }
        }
    });
});

/*----------------------------creator card menu toggle end--------------------------*/
