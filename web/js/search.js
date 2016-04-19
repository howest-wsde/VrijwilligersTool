$(document).ready(function () {
    $('#searchq').click(function(){
        $("#divResult").fadeIn();
    });

    $(document).on("click", function(e) {
        if (! $(e.target).hasClass("search")){
            $("#divResult").fadeOut();
        }
    });
});

$(function(){
    $("#searchq").keyup(function()
    {
        var searchTerm = $(this).val();
        if (searchTerm != "")
        {
            var host = "http://localhost:888";
            var url = host + "/api/search";
            $.ajax({
                type: "GET",
                url: url,
                data: "q=" + searchTerm,
                cache: true,
                success: searchSucces,
                error: logError
            });
        }
    });
});

var searchSucces = function(result){
    $("#divResult").html(result).show();
    $(".display_box").on("click", function(e){
        var url = $(e.target).find("#referer").val();
        window.location.href = "http://localhost:888" + url;
    });
};

var logError = function (xhr, message, error) {
    console.log(xhr, message, error);
};
