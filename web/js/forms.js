"use strict";
(function () {
    $(document).ready(function () {
		$(".steps .steptitle").click(function(){
			$(".steps .step").removeClass("actief");
			$(this).next(".step").addClass("actief");
            $('html, body').animate({
                scrollTop: $(this).offset().top
            }, 1000);
		});
		
		if ($(".steps .step.actief").length == 0) $(".steps .step:first").addClass("actief");

		$("input[type=file]").each(function(){
			var strID = $(this).attr("id");
			var strCurrent = $("#" + strID + "_current").val();

			$(this).hide().after(
				$("<label />").addClass("filedropper").attr("for", strID).each(function(){
					if(strCurrent) $(this).css("background-image", "url(" + strCurrent + ")").addClass("hasvalue");
				})
			).change(function(){
				var oLabel = $(this).find(" + label");
				oLabel.removeClass("hasvalue");
				if (this.files && this.files[0]) {
				    var reader = new FileReader();

				    reader.onload = function (e) {
				        oLabel.css("background-image", "url(" + e.target.result + ")").addClass("hasvalue");
				    };

				    reader.readAsDataURL(this.files[0]);
				}
			});
		});
	});
})();