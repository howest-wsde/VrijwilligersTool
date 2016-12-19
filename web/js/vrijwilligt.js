$(function() {
    reloadFavClickEvent();

    var lastClickedTabs = {};

    function reloadFavClickEvent(){
        $(".fav").off("click").on("click", function() {
            favVacancyOrOrganisation(this);
            return false;
        });

        $(".nav.nav-tabs li").off("click").on("click", function(){
            var section = "#" + $(this).closest("section.part").attr("id");
            lastClickedTabs[section] = "#" + $(this).attr("id");
        });
    }

    function favVacancyOrOrganisation(self){
        var section = "#" + $(self).closest("section.part").attr("id");
        $(self).removeClass("liked").removeClass("notliked");
        var stringUrl = $(self).attr("href");

        $.ajax({
            type: "GET",
            url: stringUrl,
            data: "ajax",
            dataType: "json",
            success: function() {
                $.get(window.location.href, function(data) {
                    $(section).replaceWith($(data).find(section));
                    reloadFavClickEvent();
                    $(lastClickedTabs[section] + " a").click();
                });
            },
            knop: self
        });

        return false;
    }

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
            );
            $(this).attr("title", "");
        }
    });

    $(".lessismore").each(function(){
        $(this).find(">.lessismore").click(function(){
            $(this).parent().toggleClass("minified");
        }).parent().addClass("minified");
    })
});


function share(strURL) {
    var leftPosition, topPosition, iW=500, iH=300;
    leftPosition = (window.screen.width / 2) - ((iW / 2) + 10);
    topPosition = (window.screen.height / 2) - ((iH / 2) + 50);
    var windowFeatures = "status=no,height=" + iH + ",width=" + iW + ",resizable=yes,left=" + leftPosition + ",top=" + topPosition + ",screenX=" + leftPosition + ",screenY=" + topPosition + ",toolbar=no,menubar=no,scrollbars=no,location=no,directories=no";
    window.open(strURL,'sharer', windowFeatures);
    return false;
}