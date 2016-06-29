$(document).ready(function(){

    initThumbnailView();
    initThumbnailPage();
    initCommentButton();

    function initThumbnailView(){
        $(document).on("click",".thumbs_view",function(){
            var countkind = $(this).data("value"),//if you want the other count ajax increase, add this switch to object
                contentkind = $(this).data("kind");
            var stat_check = $(this).hasClass("selected");
            //console.log(contentkind);

            like_count_up(countkind, stat_check, CONNUM_PARAM, CATE_PARAM, contentkind);
        })
    }

    function initThumbnailPage(){
        $(document).on("click",".thumbs_page",function(){
            var countkind = $(this).data("value"),//if you want the other count ajax increase, add this switch to object
                contentkind = $(this).data("kind"),
                contents_parents = $(this).parents('.contents_card'),
                contents_number = contents_parents.data('conno'),
                contents_category = contents_parents.data('cate');
            var stat_check = $(this).hasClass("selected");

            like_count_up(countkind, stat_check, contents_number, contents_category, contentkind);
        })
    }

    function initCommentButton(){
        $("#comment_bt").on("click",function(){
            var input = $(this).prev("#comment_text"),
                content = input.val(),
                countkind = "comment",
                stat_check = true;
            input.val(null); // INIT INPUT
            comment_write(countkind, stat_check, CONNUM_PARAM, CATE_PARAM, content);
        });
    }

    function like_count_up(countkind, stat_check, conno, catename, contentkind) {
        $.ajax({
            type: "POST",
            url: "../ajax/increase_like_ajax.php",
            data: 'countkind=' + countkind + '&conno=' + conno + '&cate=' + catename + '&stat_check=' + stat_check + '&contentkind=' + contentkind,// data send
            cache: false,
            success: function (data) {
                like_number = $("#"+countkind+"Count");
                stat_check = stat_check ? 1 : -1;
                like_number.text( Number(like_number.text()) + stat_check);
                console.log(data);
            }
        })
    }
    function comment_write(countkind,stat_check, conno, catename, content){
        $.ajax({
            type: "POST",
            url: "../ajax/comment_write_ajax.php",
            data: 'conno=' + conno + '&cate=' + catename + '&content=' + content + '&countkind=' + countkind + '&stat_check=' + stat_check, //data send
            cache: false,
            success: function (data) {
                //console.log(data);
                result = JSON.parse(data);
                console.log(result);
                comment_layout =
                '<div class="comment-div"><figure class="comment-pic"><img src="' + result.src + '"></figure><h4>' + result.name + '</h4><p class="comment-time"><span class="comment-time-counter">' + result.date + '</span></p><p class="comment-contents">' + result.content + '</p></div>'
                $(".comment-list").append(comment_layout);
            }
        })
    }
});

