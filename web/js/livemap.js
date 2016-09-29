"use strict";

let geocoder;
var mapcanvas = $("#map-preview");
var mapOptions = {
zoom: 15,
center: {lat: 50.948352, lng: 3.131108},  //this default to a map of the centre of Roeselare
streetViewControl: false,
mapTypeControl: false
};
var map = new google.maps.Map(mapcanvas[0], mapOptions);
var marker;

var codeAddress = function() {

    var address = getAddress();
    address = address.street + " " +
        address.number + " " +
        address.bus + " " +
        address.postalcode + " " +
        address.city;

    geocoder.geocode({'address': address}, function (results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            map.setCenter(results[0].geometry.location);
            if (marker)
                marker.setMap(null);
            marker = new google.maps.Marker({
                map: map,
                position: results[0].geometry.location,
                draggable: true
            });
            google.maps.event.addListener(marker, "dragend", function () {
                document.getElementById('lat').value = marker.getPosition().lat();
                document.getElementById('lng').value = marker.getPosition().lng();
            });
            if (document.getElementById('lat') !== null) {
                document.getElementById('lat').value = marker.getPosition().lat();
                document.getElementById('lng').value = marker.getPosition().lng();
            }
        } else {
            alert('Geocode was not successful for the following reason: ' + status);
        }
    });
}

var getAddress = ()=> {
    return {
        street: $("#street").find("input").val(),
        number: $("#number").find("input").val(),
        bus: $("#bus").find("input").val(),
        postalcode: $("#postalcode").find("input").val(),
        city: $("#city").find("input").val()
    }
};

$(document).ready(function(){
    geocoder = new google.maps.Geocoder();
    codeAddress();
    $("#address").on('change',function(){codeAddress();});
});
