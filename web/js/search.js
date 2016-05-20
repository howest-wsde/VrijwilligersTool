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
        openPopup();
    });

    $("#search_clipboard").on("click",function(){
        copyToClipboard(window.location.href);
        alert("De link staat nu op je klembord!");
    });

    $("#search_close").on("click",function(){
        closePopup();
    });

    var closePopup = function(){
        $("#light").css("display","none");
        $('#fade').css("display","none");
    };

    var openPopup = function(){
        $("#light").css("display","block");
        $('#fade').css("display","block");
        $("#search_url").val(window.location.href);
    };

    var copyToClipboard = function(text) {
        if (window.clipboardData && window.clipboardData.setData) {
            return clipboardData.setData("Text", text);
        }
        else if (document.queryCommandSupported && document.queryCommandSupported("copy")) {
            var textarea = document.createElement("textarea");
            textarea.textContent = text;
            textarea.style.position = "fixed";

            document.body.appendChild(textarea);

            textarea.select();
            
            try {
                return document.execCommand("copy");
            } catch (ex) {
                console.warn("Copy to clipboard failed.", ex);
                return false;
            } finally {
                document.body.removeChild(textarea);
            }
        }
    }
})();
