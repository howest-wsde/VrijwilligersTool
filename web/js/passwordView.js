"use strict";

(function () {
    $(document).ready(function () {
        $.getScript("https://cdnjs.cloudflare.com/ajax/libs/hideshowpassword/2.0.8/hideShowPassword.min.js", function(){
           console.log("HideShowPassword library loaded");
        });

        let passwordfields = $('input[type="password"]');

        passwordfields.each(function(){
            $(this).wrap('<div class="has-feedback has-clear"></div>')
                   .addClass("form-control")
                   .after('<i class="form-control-clear glyphicon glyphicon-eye-close form-control-feedback"></i>');
        });

        $('.form-control-clear').on('click',function(field){
            $(this).toggleClass('glyphicon-eye-close')
                   .toggleClass('glyphicon-eye-open');
            $(this).prev().togglePassword();
        })
    });
})();

