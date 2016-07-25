$(document).ready(function(){
    $("#loading_icon").show();
    $.when(
        loadCategoryList(initCategory, CATE_PARAM)
    ).then(function(){
        console.log(1);
        Request({
    	    url: "./service/controller/infinite_scroll/controller.php",
            data: new GET_CONTENTS("contents",0),
    	    callback: init
    	});
    });

    function init(response){
        //var detector = new InfiniteScrollDetector(new GET_CONTENTS("content",0));
        var data = response.result;
        $("#loading_icon").hide();
        console.log(data);

        addCard(data);
        //detector.start(addCard);
        $("#loading_icon").hide();
    }

    function addCard(data){
        console.log(data);
        var cardWrapper = $("#contents_box").find(".contents_wrap"),
			list = $("<li/>");

		for(var i = 0; i < data.content.length; i++){
			var card = new ContentsCard(data.content[i]);
            var cardDOM = card.render();
			list.clone(true).append(cardDOM).appendTo(cardWrapper);
		}

        console.log("VIEW : GET DATA------------------");
		console.log(data);
    }

    function initCategory(data){
        console.log(data);
        for(var i = 0; i < data.length; i++){
            var o = $("<option/>",{ "html" : data[i].name, "value" : data[i].name, "data-value" : data[i].code });
            o.appendTo($(".categoryFilter"));
        }
        $(".categoryFilter").lubySelector({
            id:"categoryFilter",
            width: 230,
            icon: "fa fa-bars",
            searchBar: true,
            optGroup: true,
            theme: "rect",
            changeEvent: change
        });
        function change(){
            var v = $(this).lubySelector("getValueByIndex");
            setUrlParameter("mid_cate", v);
        }
    }
});
