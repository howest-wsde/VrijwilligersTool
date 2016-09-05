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
    $(".social li.facebook a").click(function() {
        return share('http://www.facebook.com/sharer.php?u='+encodeURIComponent($(this).attr("href"))+'&t=');
    });
    $(".social li.twitter a").click(function() {
        return share("https://twitter.com/intent/tweet?source=tweetbutton&original_referer="+encodeURIComponent($(this).attr("href"))+"&url="+encodeURIComponent($(this).attr("href"))+"");
    });
    $(".social li.googleplus a").click(function() {
        return share("https://plus.google.com/share?url="+encodeURIComponent($(this).attr("href")));
    });

    $("span.info").mouseover(function(){
        var strInfo = $(this).attr("title");
        if ($(this).find(".infopop").show().length == 0) {
            $(this).append(
                $("<div />").addClass("infopop").html(strInfo).mouseout(function(){
                    $(this).hide();
                })
            )
        }
    });
});


function share(strURL) {
    var leftPosition, topPosition;
    iW=500, iH=300;
    leftPosition = (window.screen.width / 2) - ((iW / 2) + 10);
    topPosition = (window.screen.height / 2) - ((iH / 2) + 50);
    var windowFeatures = "status=no,height=" + iH + ",width=" + iW + ",resizable=yes,left=" + leftPosition + ",top=" + topPosition + ",screenX=" + leftPosition + ",screenY=" + topPosition + ",toolbar=no,menubar=no,scrollbars=no,location=no,directories=no";
    window.open(strURL,'sharer', windowFeatures);
    return false;
}