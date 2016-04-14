var SocialMediaLogin = {
    
    init: function(){
        this.facebook.init();
    },
    
    facebook: {
        init: function(){
            this.loadfb();
        },

        statusChangeCallback: function(response) {
            console.log('statusChangeCallback');
            console.log(response);
            if (response.status === 'connected') {
                this.testAPI();
            } else if (response.status === 'not_authorized') {
                document.getElementById('status').innerHTML = 'Please log ' +
                    'into this app.';
            } else {
                document.getElementById('status').innerHTML = 'Please log ' +
                    'into Facebook.';
            }
        },
        
        getLoginStatus: function(){
            FB.getLoginStatus(function(response) {
                this.statusChangeCallback(response);
            });
        },
        
        checkLoginState: function(){
            this.getLoginStatus();
        },
        
        loadfb: function(){
            var self = this;
            window.fbAsyncInit = function() {
                FB.init({
                    appId: window.fb_id,
                    cookie: true,
                    xfbml: true,
                    version: 'v2.5'
                });
                self.getLoginStatus();
            };

            (function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s); js.id = id;
                js.src = "//connect.facebook.net/en_US/sdk.js";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        },
        
        testAPI: function(){
            FB.api('/me', 'get', {fields: 'name,email, gender'}, function (response) {
                console.log("Facebook Name: " + response.name);
                console.log("Facebook Email: " + response.email);
                console.log("Facebook Gender: " + response.gender);
            });
        }
            
    },

    google: {
        onSignIn: function(googleUser){
            var profile = googleUser.getBasicProfile();
            console.log("Google ID: " + profile.getId());
            console.log('Google Full Name: ' + profile.getName());
            console.log('Google Given Name: ' + profile.getGivenName());
            console.log('Google Family Name: ' + profile.getFamilyName());
            console.log("Google Image URL: " + profile.getImageUrl());
            console.log("Google Email: " + profile.getEmail());
        }
    },

    linkedin: {
        onlLinkedInLoad: function(){
            IN.Event.on(IN, "auth", this.getProfileData);
        },
        
        onSuccess: function(data){
            console.log(data);
        },
        
        onError: function(error){
            console.log(error);
        },
        
        getProfileData: function() {
            IN.API.Profile("me").fields("first-name", "last-name", "email-address").result(function (data) {
                console.log(data);
            }).error(function (data) {
                console.log(data);
            });
        }
    }
};

var pageInitialized = false;
$(function()
{
    if(pageInitialized) return;
    pageInitialized = true;
    SocialMediaLogin.init();
});