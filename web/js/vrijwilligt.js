$(function() {
    $(".fav").click(function() {
    	$(this).removeClass("liked").removeClass("notliked");
    	oItem = this;
    	strURL = $(this).attr("href");
    	$.ajax({
            type: "GET",
            url: strURL,
            data: "ajax",
            success: function(result) {
                $(oItem).addClass(result)
            },
        });
        return false;
    });
});

