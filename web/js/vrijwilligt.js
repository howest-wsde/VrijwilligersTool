$(function() {
    $(".fav").click(function() {
    	$(this).removeClass("liked").removeClass("notliked");
    	strURL = $(this).attr("href");
    	$.ajax({
            type: "GET",
            url: strURL,
            data: "ajax",
  			dataType: "json",
            success: function(result) {
            	$(this.knop).addClass(result.class).attr("href", result.url).attr("title", result.text);
            },
            knop: this,
        });
        return false;
    });
});

