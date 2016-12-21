"use strict";
(function () {
    var $usedForm;

    $('form').on("submit", function(event){
        event.preventDefault();

        $usedForm = this;
        var blob = $('input:file')[0].files[0];

        if (typeof blob == "undefined"){
            submitForm();
        } else {
            var fileReader = new FileReader();
            fileReader.onloadend = function (e) {
                var arr = (new Uint8Array(e.target.result)).subarray(0, 4);
                var header = "";
                for (var i = 0; i < arr.length; i++) {
                    header += arr[i].toString(16);
                }

                switch (header) {
                    case "89504e47": //image/png
                    case "ffd8ffe0": //image/jpeg
                    case "ffd8ffe1": //image/jpeg
                    case "ffd8ffe2": //image/jpeg
                        submitForm();
                        return;
                    default:
                        alert(translations.not_valid);
                        return;
                }

                if (blob.size > 2000000){
                    alert(translations.too_large);
                } else {
                    submitForm();
                }
            };

            fileReader.readAsArrayBuffer(blob);
        }
    });

    function submitForm(){
        $usedForm.submit();
    }
})();