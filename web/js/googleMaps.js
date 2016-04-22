"use strict";
var googleMaps = (function(window,undefined) {

    var markers = []; //global array to fit map
    var mapOptions = {
    zoom: 15,
    center: {lat: 50.948352, lng: 3.131108}
    //this default to a map of the centre of Roeselare
    };
    var mapcanvas = $("#map-canvas");
    var map = new google.maps.Map(mapcanvas[0], mapOptions);
    var userAddress = mapcanvas.data("useraddress");
    var vacancyAddress = $("#location").text();

    function googleMaps() {
        this.addAddressToMap = function addAdressToMap(address){//geocodes an address and adds it to the map
            let geocoder = new google.maps.Geocoder();
            geocoder.geocode({'address': address}, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    //if ok -> add retrieved coords as marker on map
                    map.setCenter(results[0].geometry.location);
                    var marker = new google.maps.Marker({
                        map: map,
                        animation: google.maps.Animation.DROP,
                        position: results[0].geometry.location
                    });
                    markers.push(marker);
                } else {
                    console.log('%cGeocode was not successful for the following reason: \n ' +
                        `%c${status}`,
                        'font-weight:bold; color:red;');
                }
            })
        };
        this.init = function init(){
            if(userAddress !== " ") // user is not logged in aka da    ta attr couldn't be filled
                this.addAddressToMap(userAddress);
            this.addAddressToMap(vacancyAddress);
        };
    }
    return googleMaps = googleMaps;
})(window);


var googleMapsModule = new googleMaps();
googleMapsModule.init();

/*
function fitMap() {
    var bounds = new google.maps.LatLngBounds();
    for (var i = 0; i < markers.length; i++) {
        bounds.extend(markers[i].getPosition());
    }
    map.fitBounds(bounds);
}
*/