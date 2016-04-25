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
                    url: searchURL,
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
})();
