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
        var message = "<h1>" + $("#vacancy_title").text() + "</h1>";
        message += "<p> Ons aanbod:";
        message += "<br>" + $("#vacancy_description").text() + "</p>";
        message += "<p>" + $("#vacancy_organisation").text();
        message += "<br>" + $("#vacancy_location").text() + "</p>";
        message += "<p> Bekijk online <a href=\"" + window.location.href + "\">hier</a>!</p>";

        //todo: mail stylen, maar zal dit maar doen na de overhaul van het designen en fancier maken :)
        var mailto_link = 'mailto:' + email + '?subject=' + subject + '&body=' + message;
        window.open(mailto_link);
    });

    //twitter
    $('article #tweet').attr("data-text", $("#vacature-title").text() + " - Interessante vacature op Roeselare Vrijwilligt!");
    $('#light #tweet').attr("data-text", "Kijk wat ik heb gevonden!");
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
    $('#share-linkedin').attr("data-url", document.URL);
});