"use strict";
(function () {
    var search = Array();
    $(document).ready(function () {

        $(document).on("click", function(e) {
            if(!$(e.target).closest("form.search").hasClass("search")) {
                $("form.search.actief").removeClass("actief");//.find(".searchResult").html("");
            }
        });

        $("form.search").submit(function(){
            if ($(this).find(".searchq").val() == ""){
                $(this).find(".searchq").focus();
                $(this).closest("form.search").addClass("actief");
                return false;
            }
        });

        $('.searchq')
            .click(function(){
                $(this).closest("form.search").addClass("actief");
            })
            .keyup(function() {
                clearTimeout(search["timer"]);
                search["term"] = $(this).val();
                search["form"] = $(this).closest("form.search");
                search["timer"] = setTimeout(function(){
                    if (search["term"] != "") {
                        $(search["form"]).find(".searchResult").html("<ul><li><span>bezig met zoeken...</span></li></ul>");
                        $.ajax({
                            type: "GET",
                            url: RV_GLOBALS.searchURL,
                            data: "q=" + search["term"],
                            cache: true,
                            success: function(result) {
                                $(search["form"]).find(".searchResult").html(result)
                            },
                            error: logError
                        });
                    } else $(search["form"]).find(".searchResult").html("");
                }, 500)
            });
    });

    var logError = function (xhr, message, error) {
        //console.log(xhr, message, error);
        $(search["form"]).find(".searchResult").html("<ul><li><span>Er gebeurde een fout tijdens het zoeken. Probeer later opnieuw</span></li></ul>");
    };

    //functionaliteit om een zoekopdracht te delen
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
