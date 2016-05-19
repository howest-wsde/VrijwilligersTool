"use strict";
$(function(){

    //Tijdelijke styling
    $(".share-buttons > div").css({
        "display":"block",
        "margin":"5px 0 5px 0"
    });
    
    //email
    $("#email").on("click",function(){
        var email = "ontvanger@vacature.com";
        var subject =$("#vacature-title").text() + " - Interessante vacature op Roeselare Vrijwilligt!";
        //var message = "Bekijk deze vacature op " + window.location.href + "%0D%0A"+ $("article").html();
        var message = "<strong>" + $("#vacature-title").text() + "</strong>";
        message += "<p>" + $("#vacature-description").text() + "</p>";
        message += "<p> Ons aanbod: </p>";
        message += "<p>" + $("#vacature-organisation").text() + "</p>";
        message += "<p>" + $("#vacature-location").text() + "</p>";
        message += "<p> Bekijk online <a href=\"" + window.location.href + "\">hier</a>!";

        //todo: mail stylen, maar zal dit maar doen na de overhaul van het designen en fancier maken :)
        var mailto_link = 'mailto:' + email + '?subject=' + subject + '&body=' + message;
        window.open(mailto_link);
    });

    //twitter
    $('#tweet').attr("data-text", $("#vacature-title").text() + " - Interessante vacature op Roeselare Vrijwilligt!");
    (function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';
        if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';
            fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs'));

    //facebook
        $('.fb-like').attr("data-href", document.URL);
        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/nl_BE/sdk.js#xfbml=1&version=v2.6&appId=990174991070590";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    
    //linkedin
    $("#share-linkedin").attr("data-url", window.location.href );
});