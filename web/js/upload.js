"use strict";
(function () {
    $('form[name="organisation"]').on("submit", function(event){
        event.preventDefault();

        var blob = document.getElementById('organisation_logoFile').files[0];

        if (typeof blob == "undefined"){
            submitForm()
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
                    case "ffd8ffe1": //image/
                    case "ffd8ffe2": //image/jpeg
                        submitForm();
                        return;
                    default:
                        alert("Gelieve een geldige afbeelding op te laden.");
                        return;
                }

                if (blob.size > 2000000){
                    alert("Gelieve een afbeelding op te laden die maximum 2MB groot is.");
                } else {
                    submitForm();
                }
            };

            fileReader.readAsArrayBuffer(blob);
        }
    });

    function submitForm(){
        $('form[name="organisation"]')[0].submit();
    }
})();