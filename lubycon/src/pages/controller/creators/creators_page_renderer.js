$(document).ready(function(){
	$("#loading_icon").show();
	Request({
	    url: "./service/controller/infinite_scroll/controller.php",
        data: {
            cardType: "creator",
    		page: "creator",
            topCate: null,
            sort: 5, //5 is userCode it is templary
    		filter: {
    			midCate: null,
    			license: null,
    			continent: null,
    			job: null,
    			search: $(".searchFilter").lubySelector("getValueByIndex")
    		},
    		searchValue: $(".search-bar-text").val() === "Enter the keyword" ? null : $(".search-bar-text").val(),
    		nowPage: getUrlParameter("page"),
            targetPage: getUrlParameter("page")
        },
	    callback: init
	});

	loadJobList(initJob);

	var detector = new InfiniteScrollDetector({
		cardType: "creator",
		page: "creator",
		sort: $(".sortFilter").lubySelector("getValueByIndex"),
		filter: {
			midCate: null,
			license: null,
			continent: $(".locationFilter").lubySelector("getValueByIndex") === 0 ?
				"all" :
				$(".locationFilter").lubySelector("getValueByindex"),
			job: $(".jobFilter").lubySelector("getValueByIndex") === 0 ?
				"all" :
				$(".jobFilter").lubySelector("getValueByIndex"),
			search: $(".searchFilter").lubySelector("getValueByIndex")
		},
		searchValue: $(".search-bar-text").val() === "Enter the keyword" ? null : $(".search-bar-text").val(),
		nowpage: getUrlParameter("page")
	});

	function init(response){
		var data = response.result;
		console.log(data);
		$("#loading_icon").hide();
		var cardWrapper = $("#creator_card_wrap"),
			list = $("<li/>",{ "class" : "creator_card_in" });

		data.bestCreator[0].bestCreator = true;
		var bestCreator = new CreatorCard(data.bestCreator[0]).render();
		list.clone(true).append(bestCreator).appendTo(cardWrapper);

		addCard(data);
		detector.start(addCard);
	}

	function addCard(data){
		var cardWrapper = $("#creator_card_wrap"),
			list = $("<li/>",{ "class" : "creator_card_in" });

		for(var i = 0; i < data.content.length; i++){
			var card = new CreatorCard(data.content[i]).render();
			list.clone(true).append(card).appendTo(cardWrapper);
		}
		console.log("VIEW : GET DATA------------------");
		console.log(data);
	}
	function initJob(data){
		console.log(data);
		for(var i = 0; i < data.length; i++){
			var o = $("<option/>", { "html" : data[i].name });
			o.appendTo($(".jobFilter"));
		}
		$(".jobFilter").lubySelector({
			id: "jobFilter",
			width: 200,
			icon: "fa fa-suitcase",
			theme: "rect",
			changeEvent: change
		});

		function change(){
			var v = $(this).lubySelector("getValueByIndex");
            setUrlParameter("job", v);
		}
	}
});