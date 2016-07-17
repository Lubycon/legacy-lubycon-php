console.log("INFINITE SCROLL DETECTOR IS READY");
var InfiniteScrollDetector = function(data){
    console.log(data);
    this.cardType = data.cardType;
    this.page = data.page;
    this.topCate = data.topCate || null;
    this.filter = {
        midCate: data.filter.midCate,
        sort: data.filter.sort,
        license: data.filter.license,
        continent: data.filter.continent,
        job: data.filter.job,
        search: data.filter.search
    };
    this.searchValue = data.searchValue;
    this.nowPage = data.nowpage;
    this.targetPage = null;
};
InfiniteScrollDetector.prototype.setDate = function(p,v){
    switch(p){
        case "cardType" : this.cardType = v; break;
        case "page" : this.page = v; break;
        case "topCate" : this.topCate = v; break;
        case "midCate" : this.midCate = v; break;
        case "filter.sort" : this.filter.sort = v; break;
        case "filter.license" : this.filter.license = v; break;
        case "filter.continent" : this.fliter.continent = v; break;
        case "serach.filter" : this.search.filter = v; break;
        case "serach.value" : this.search.value = v; break;
    }
};
InfiniteScrollDetector.prototype.getData = function(){
    return {
        cardType: this.cardType,
        page: this.page,
        topCate: this.topCate,
        filter: {
            midCate: this.filter.midCate,
            sort: this.filter.sort,
            license: this.filter.license,
            continent: this.filter.continent,
            job: this.filter.job,
            search: this.filter.search
        },
        searchValue: this.searchValue,
        nowPage: this.nowPage,
        targetPage: this.targetPage
    };
};
InfiniteScrollDetector.prototype.next = function(callback){
    this.nowPage = getUrlParameter("page") || 1;
    this.targetPage = this.nowPage + 1;
    console.log(this.getData());
    Controller({
        url: "./pages/controller/creators/controller.php",
        data: this.getData(),
	    callback: callback
    });
};
InfiniteScrollDetector.prototype.prev = function(callback){
    this.nowPage = getUrlParameter("page") || 1;
    this.targetPage = this.nowPage - 1;
    Controller({
        url: "./pages/controller/creators/controller.php",
        data: this.getData(),
	    callback: callback
    });
};
InfiniteScrollDetector.prototype.start = function(callback){
    var _this = this;
    $(document).on("scroll",detect);
    function detect(){
        if($(window).scrollTop() === $(document).height() - window.innerHeight){
            console.log("DOWN");
            _this.next(callback);
            return false;
        }
        else if($(document).scrollTop() === 0){
            console.log("UP");
            _this.prev(callback);
            return false;
        }
    }
};
