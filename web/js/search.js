"use strict";
(function () {
    var host = "http://localhost:888";

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
        var searchTerm = $(this).val();
        if (searchTerm != "") {
            $.ajax({
                type: "GET",
                url: host + "/api/search",
                data: "q=" + searchTerm,
                cache: true,
                success: searchSucces,
                error: logError
            });
        }
    };

    var searchSucces = function(result) {
        $("#divResult").html(result).show();
        $(".display_box").on("click", function(e){
            var url = $(e.target).find("#referer").val();
            window.location.href = host + url;
        });
    };

    var logError = function (xhr, message, error) {
        console.log(xhr, message, error);
    };
})();
