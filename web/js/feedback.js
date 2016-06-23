$(document).ready(function() {
    var $dragging = null;

    $(document.body).on("mousemove", function(e) {
        if ($dragging) {
            $dragging.offset({
                top: e.pageY - 50,
                left: e.pageX - 50
            });
      		return false; 
        }
    });


    $(document.body).on("mousedown", "form.feedbackform fieldset", function (e) {
        $dragging = $("form.feedbackform fieldset"); 
    });

    $(document.body).on("mouseup", function (e) {
        $dragging = null;
    });
}); 