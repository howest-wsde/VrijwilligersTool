"use strict";
(function () {
    $(document).ready(function () {
		$(".steps .step").click(function(){
			$(".steps .step").removeClass("actief");
			$(this).addClass("actief");
		})
		if ($(".steps .step.actief").length == 0) $(".steps .step:first").addClass("actief");

		$("input[type=file]").each(function(){
			var strID = $(this).attr("id");
			$(this).hide().after(
				$("<label />").addClass("filedropper").attr("for", strID).html("kies een bestand")
			).change(function(){
				var oLabel = $(this).find(" + label");
				oLabel.html($(this).val()).removeClass("hasvalue");
				if (this.files && this.files[0]) {
				    var reader = new FileReader();

				    reader.onload = function (e) {
				        oLabel.css("background-image", "url(" + e.target.result + ")").addClass("hasvalue");
				    }

				    reader.readAsDataURL(this.files[0]);
				}
			})
		});
	})
})();