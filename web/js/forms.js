"use strict";
(function () {  
    $(document).ready(function () {
		$(".steps .step").click(function(){
			$(".steps .step").removeClass("actief");
			$(this).addClass("actief"); 
		})
		if ($(".steps .step.actief").length == 0) $(".steps .step:first").addClass("actief"); 
	})
})();