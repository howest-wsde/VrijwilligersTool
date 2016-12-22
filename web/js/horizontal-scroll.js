"use strict";

$(document).ready(function () {
    let scrollbox = $(".arrow-scrollable");
    if(scrollbox.length !== 0){ //there are elements that need arrows

        scrollbox.wrap("<div class='scrollcontainer'></div>");

        $(".scrollcontainer").after("<span class='glyphicon glyphicon-chevron-left left' aria-hidden='true'/>")
                             .after("<span class='glyphicon glyphicon-chevron-right right' aria-hidden='true'/>");

        scrollbox.css({"overflow": "hidden"});

        $(".left").css({
            "cursor" : "default",
            "float": "left",
            "top": "50%",
            "transform" : "translateY(-50%)"
        });

        $(".right").css({
            "cursor" : "default",
            "float": "right",
            "top": "50%",
            "transform" : "translateY(-50%)"
        });

        $(".right").on('click', function () {
            console.log("right");
            $(this).closest(".scrollcontainer").find(".arrow-scrollable").css({"color" : "red"});
            //scrollbox.animate({scrollLeft: '+=313'}, 333);
        });

        $(".left").on('click', function () {
            console.log("left");
            scrollbox.animate({scrollLeft: '-=313'}, 333);
        });

    }else console.log("Arrow horizontal scrolling loaded but no elements were found needing this!");

});