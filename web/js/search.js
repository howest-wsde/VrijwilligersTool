"use strict";
(function () { 
    var search = Array(); 
    $(document).ready(function () {
        $('#searchq').click(function(){
            $("#divResult").fadeIn();
        });

        $(document).on("click", function(e) {
            if (! $(e.target).hasClass("search")) {
                $("#divResult").fadeOut();
            }
        });

        $("#searchq").keyup(search);
    });

    var search = function() {
        clearTimeout(search["timer"]); 
        search["term"] = $(this).val();
        search["timer"] = setTimeout(function(){  
            if (search["term"] != "") {
                $.ajax({
                    type: "GET",
                    url: RV_GLOBALS.searchURL,
                    data: "q=" + search["term"],
                    cache: true,
                    success: searchSucces,
                    error: logError
                });
            } else $("#divResult").fadeOut();
        }, 500)
    };

    var searchSucces = function(result) {
        $("#divResult").html(result).show();
        $(".display_box").on("click", function(e){
            var url = $(e.target).find("#referer").val();
            window.location.href = url;
        });
    };

    var logError = function (xhr, message, error) {
        console.log(xhr, message, error);
    };

    $("#share_search").on("click", function(){
        document.getElementById('light').style.display='block';document.getElementById('fade').style.display='block';
        $("#search_url").val(window.location.href);
    });

    $("#search_clipboard").on("click",function(){
        copyToClipboard(window.location.href);
        alert("De url staat nu op je klembord!");
    });

    $("#search_close").on("click",function(){
        document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none';
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
    $('#share-linkedin').attr("data-url", window.location.href);

    $(".share-buttons > div").css({
        "display":"block",
        "margin":"5px 0 5px 0"
    });

    $(".black_overlay").css({
        "display": "none",
        "position": "fixed",
        "top": "0%",
        "left": "0%",
        "width": "100%",
        "height": "100vh",
        "background-color": "black",
        "z-index": "1001",
        "-moz-opacity": "0.8",
        "opacity":".80",
        "filter": "alpha(opacity=80)"
    });

    $(".white_content").css({
        "display": "none",
        "position": "fixed",
        "top": "25%",
        "left": "40%",
        "width": "20%",
        "height": "50vh",
        "padding": "1vh",
        "border": "5vh solid #efefef",
        "background-color": "white",
        "z-index":"1002",
        "overflow": "auto",
    });

    function copyToClipboard(text) {
        if (window.clipboardData && window.clipboardData.setData) {
            // IE specific code path to prevent textarea being shown while dialog is visible.
            return clipboardData.setData("Text", text);

        } else if (document.queryCommandSupported && document.queryCommandSupported("copy")) {
            var textarea = document.createElement("textarea");
            textarea.textContent = text;
            textarea.style.position = "fixed";  // Prevent scrolling to bottom of page in MS Edge.
            document.body.appendChild(textarea);
            textarea.select();
            try {
                return document.execCommand("copy");  // Security exception may be thrown by some browsers.
            } catch (ex) {
                console.warn("Copy to clipboard failed.", ex);
                return false;
            } finally {
                document.body.removeChild(textarea);
            }
        }
    }
})();
