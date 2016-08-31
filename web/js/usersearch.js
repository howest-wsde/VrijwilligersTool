"use strict";
(function () { 
    var usersearch = Array(); 
    $(document).ready(function () { 
        $("input.usersearch").keyup(doUsersearch);
        usersearch["form"] = $("form.usersearch"); 
        usersearch["field"] = $("div.usersearch");  
    });

    var doUsersearch = function() {
        clearTimeout(usersearch["timer"]); 
        usersearch["term"] = $(this).val();  
        usersearch["timer"] = setTimeout(function(){  

            if (usersearch["term"] != "") {
                $(usersearch["field"]).html("loading... ");
                $.ajax({
                    type: usersearch["form"].attr("method"),
                    url: RV_GLOBALS.usersearchURL,
                    data: usersearch["form"].serialize(),
                    cache: true,
                    success: usersearchSucces, 
                });
            } else $(usersearch["field"]).html("");
        }, 500)
    };

    var usersearchSucces = function(result) {
        $(usersearch["field"]).html(result); 
    };
 
})();
