"use strict";
(function () {
    $(document).ready(function () {
        $("button[name^='person[submit']").on("click", function() {
            console.log("clicked");
            $(".step").addClass("actief");
        });
	});
})();