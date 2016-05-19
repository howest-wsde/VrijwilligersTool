//note: the facebook sdk has already been initialised on the loging page
//"use strict"; --> geeft errors, zonder werkt het wel :-/

// initialize Account Kit with CSRF protection
AccountKit_OnInteractive = function(){
    AccountKit.init(
        {
            appId: 1870185456541904,
            state:"csrf",
            version:"v2.6"
        }
        //If your Account Kit configuration requires app_secret, you have to include ir above
    );
};
// login callback
function loginCallback(response) {
    console.log(response);
    if (response.status === "PARTIALLY_AUTHENTICATED") {
        document.getElementById("code").value = response.code;
        document.getElementById("csrf_nonce").value = response.state;
        document.getElementById("my_form").submit();
    }
    else if (response.status === "NOT_AUTHENTICATED") {
        // handle authentication failure
        console.log("Authentication failure");
    }
    else if (response.status === "BAD_PARAMS") {
        // handle bad parameters
        console.log("Bad parameters");
    }
}
// phone form submission handler
function phone_btn_onclick() {
    // you can add countryCode and phoneNumber to set values
    AccountKit.login('PHONE', // will use default values if this is not specified
        loginCallback);
}
// email form submission handler
function email_btn_onclick() {
    // you can add emailAddress to set value
    AccountKit.login('EMAIL', loginCallback);
}
// destroying session
function logout() {
    document.location = 'logout.php';
}
