"use strict";
var googleMaps = (function(window,undefined) {
    var markers = []; //array to fit map
    var mapOptions = {
    zoom: 15,
    center: {lat: 50.948352, lng: 3.131108}
    //this default to a map of the centre of Roeselare
    };
    var mapcanvas = $("#map-canvas");
    var map = new google.maps.Map(mapcanvas[0], mapOptions);
    var userAddress = mapcanvas.data("useraddress");
    var vacancyAddress = $("#location").text();
    var fitmap = ()=> {
        var bounds = new google.maps.LatLngBounds();
        for (var i = 0; i < markers.length; i++) {
            bounds.extend(markers[i].getPosition());
        }
        map.fitBounds(bounds);
    };

    //functions accessible by objects of this module
    function googleMaps() {
        var self = this;
        this.addAddressToMap = (address)=> {//geocodes an address and adds it to the map
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
                    fitmap();
                    self.drawRoute();
                } else {
                    console.log('%cGeocode was not successful for the following reason: \n ' +
                        `%c${status}`,
                        'font-weight:bold; color:red;');
                }
            })
        };
        this.drawRoute = ()=> {
            var directionsDisplay = new google.maps.DirectionsRenderer();
            var directionsService = new google.maps.DirectionsService();
            var request = {
                origin: userAddress,
                destination: vacancyAddress,
                travelMode: google.maps.TravelMode.DRIVING
            };
            if (userAddress != " ") {
                directionsService.route(request, function (result, status) {
                    if (status == google.maps.DirectionsStatus.OK) {
                        directionsDisplay.setMap(map);
                        directionsDisplay.setDirections(result);
                    }
                    else {
                        console.log('%Couldn\'t get direction because: \n ' +
                            `%c${status}`,
                            'font-weight:bold; color:red;');
                    }
                });
            }
            else {
                console.log("user is not logged in thus no directions available");
            }
        };
        this.init = ()=> {
            if (userAddress !== " ") // user is not logged in aka data attr couldn't be filled
                this.addAddressToMap(userAddress);
            this.addAddressToMap(vacancyAddress);
        };
    }
    return googleMaps = googleMaps;
})(window);

var googleMapsModule = new googleMaps;
googleMapsModule.init();


//TODO:
// change api key,
// add sprite to markers,
// add options for mode (walking, driving, biking)
//
