window.fbAsyncInit = function () {
    FB.init({
        appId: '990174991070590',
        xfbml: true,
        version: 'v2.5'
    });
};

(function (d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) {
        return;
    }
    js = d.createElement(s);
    js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

window.fbAsyncInit = function () {
    FB.init({
        appId: '990174991070590',
        cookie: true,
        xfbml: true,
        version: 'v2.5'
    });

    FB.getLoginStatus(function (response) {
        statusChangeCallback(response);
    });
};

(function (d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s);
    js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

function statusChangeCallback(response) {
    window.fbAsyncInit = function () {
        FB.init({
            appId: '990174991070590',
            xfbml: true,
            version: 'v2.5'
        });
    };
}

(function (d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) {
        return;
    }
    js = d.createElement(s);
    js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

window.fbAsyncInit = function () {
    FB.init({
        appId: '990174991070590',
        cookie: true,
        xfbml: true,
        version: 'v2.5'
    });

    FB.getLoginStatus(function (response) {
        statusChangeCallback(response);
    });
};

(function (d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s);
    js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

function checkLoginState() {
    FB.getLoginStatus(function (response) {
        statusChangeCallback(response);
    });
};

function testAPI(){
    FB.api('/me', 'get', {fields: 'name,email, gender'}, function (response) {
        console.log("Facebook Name: " + response.name);
        console.log("Facebook Email: " + response.email);
        console.log("Facebook Email: " + response.gender);
    });
};

function onSignIn(googleUser){
    var profile = googleUser.getBasicProfile();
    console.log("Google ID: " + profile.getId());
    console.log('Google Full Name: ' + profile.getName());
    console.log('Google Given Name: ' + profile.getGivenName());
    console.log('Google Family Name: ' + profile.getFamilyName());
    console.log("Google Image URL: " + profile.getImageUrl());
    console.log("Google Email: " + profile.getEmail());
};