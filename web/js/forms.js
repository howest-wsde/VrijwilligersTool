"use strict";
(function () {  
    $(document).ready(function () {
		$(".steps .step").click(function(){
			$(".steps .step").removeClass("actief");
			$(this).addClass("actief"); 
		})
	})
})();