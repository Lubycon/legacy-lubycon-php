<div class="no-data-wrapper">
	<i class="fa fa-inbox"></i>
	<p>There is no contents.</p>
</div>
<script>
	var c = getUrlParameter("cate");
	if(c === "artwork" || c === "vector" || c === "threed" || c === "my_contents"){
		var button = $("<div/>",{ "class" : "viewmore_bt", "html" : "UPLOAD CONTENTS" }).on("click",function(){
			$("#addcontent_bt").trigger("click");
		});
		$(".no-data-wrapper").append(button);
	}	
</script>