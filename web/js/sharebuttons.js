"use strict";
$(function(){

    //email
    $("#email").on("click",()=>{
        var email = "ontvanger@vacature.com";
        var subject ="Bekijk deze vacature";
        var message = `Bekijk <h1>deze</h1> vacature op ${window.location.href}
                       ${$("article").text()}`;
        
        //todo: mail stylen, maar zal dit maar doen na de overhaul van het designen en fancier maken :)
        var mailto_link = 'mailto:' + email + '?subject=' + subject + '&body=' + message;
        window.open(mailto_link);
    });

    //twitter
    !function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';
        if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';
            fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');

    //facebook
    !function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5&appId=129814425951";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk');



});