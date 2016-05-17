"use strict";
(function () {
    $(document).ready(function () {
        $('.recentSearch').click(function(e){
            var baseId = "search_filter_";

            var term = $(e.target).text();
            $("#" + baseId + "term").val(term);

            $(e.target).children().each(function(index, input){
                var typeName = $(input).attr("name");
                var checked = $(input).attr("value");

                $("#" + baseId + typeName).prop('checked', checked);;
            });

            $('[name="search_filter"]').submit();
        });
    })
})();
