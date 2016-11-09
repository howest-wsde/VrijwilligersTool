"use strict";

var geocoder;

var codeAddress = function() {
    var mapcanvas = $("#map-all");
    var mapOptions = {
        zoom: 15,
        center: {lat: 50.948352, lng: 3.131108},
        streetViewControl: false,
        mapTypeControl: false
    };
    var map = new google.maps.Map(mapcanvas[0], mapOptions);
    var marker;
    var address = "Roeselare";

    geocoder.geocode({'address': address}, function (results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            map.setCenter(results[0].geometry.location);


            if (document.getElementById('lat') !== null) {
                document.getElementById('lat').value = marker.getPosition().lat();
                document.getElementById('lng').value = marker.getPosition().lng();
            }
        } else {
            console.log('Geocode was not successful for the following reason: ' + status);
        }
    });

    var geocodePosition = function(marker) {
        let lat = marker.getPosition().lat();
        let lng = marker.getPosition().lng();

        geocoder.geocode({'location': {lat: parseFloat(lat), lng: parseFloat(lng)} }, function(results, status) {
            if (status === 'OK') {
                if (results[1]) {
                    $("#edit_person_number").val(results[0].address_components[0].long_name);
                    $("#edit_person_street").val(results[0].address_components[1].long_name);
                    $("#edit_person_city").val(results[0].address_components[2].long_name);
                    $("#edit_person_postalcode").val(results[0].address_components[6].long_name);
                } else {
                    console.log('No results found');
                }
            } else {
                console.log('Geocoder failed due to: ' + status);
            }
        });
    }
};


$(document).ready(function(){

    geocoder = new google.maps.Geocoder();
    codeAddress();
    $("#address").on('change',function(){codeAddress();});
});
