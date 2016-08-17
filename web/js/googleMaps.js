"use strict";
var googleMaps = (function(window,undefined) {
    var directionsDisplay = new google.maps.DirectionsRenderer();
    var directionsService = new google.maps.DirectionsService();

    var mapcanvas = $("#map-canvas");
    var markers = []; //array to fit map
    var mapOptions = {
    zoom: 15,
    center: {lat: 50.948352, lng: 3.131108}
    //this default to a map of the centre of Roeselare
    };

    var map = new google.maps.Map(mapcanvas[0], mapOptions);

    var userAddress = mapcanvas.data("useraddress");
    var vacancyAddress = $("#targetaddress").text();

    var fitmap = ()=> {//resizes the map to fit the start and end in one view
        var bounds = new google.maps.LatLngBounds();
        for (var i = 0; i < markers.length; i++) {
            bounds.extend(markers[i].getPosition());
        }
        map.fitBounds(bounds);
    };

    //functions accessible by objects of this module
    function googleMaps() {
        var self = this;
        this.addAddressToMap = (address,markericon)=> {//geocodes an address and adds it to the map
            let geocoder = new google.maps.Geocoder();
            geocoder.geocode({'address': address}, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    //if ok -> add retrieved coords as marker on map
                    map.setCenter(results[0].geometry.location);
                    var marker = new google.maps.Marker({
                        map: map,
                        animation: google.maps.Animation.DROP,
                        position: results[0].geometry.location,
                        //icon: markericon
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
            directionsDisplay.setMap(null); // clears the map for another new route
            var selectedMode = $("#transitMethod").find("option:selected").val(); //get selected mode
            var request = {
                origin: userAddress,
                destination: vacancyAddress,
                travelMode:  google.maps.TravelMode[selectedMode], //add it in the request
                transitOptions:{}
            };

            if(selectedMode === "TRANSIT"){// when transit is checked, display more options and include them in the request
                let mode = $("#transitMode");
                mode.removeClass("hidden").slideDown();
                request.transitOptions.modes = [google.maps.TransitMode[mode.val()]];
            }
            else{
                $("#transitMode").slideUp();
            }

            if (userAddress != " ") {//if user is logged in we draw the route from his house
                directionsService.route(request, function (result, status) {
                    if (status == google.maps.DirectionsStatus.OK) {
                        directionsDisplay.setMap(map);
                        directionsDisplay.setPanel($("#description")[0]);//[0] for the pure js object
                        directionsDisplay.setOptions( { suppressMarkers: true } ); //no default A and B markers when routing
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
            $("#getRoute").click((e)=>{e.preventDefault(); $("#description").toggleClass("hidden")});
            if (userAddress !== " ") // user is not logged in aka data attr couldn't be filled
                this.addAddressToMap(userAddress, "../images/homeIcon.png");
            this.addAddressToMap(vacancyAddress, "../images/vacancyIcon.png");
        };
    }
    return googleMaps = googleMaps;
})(window);

var googleMapsModule = new googleMaps;
googleMapsModule.init();



/*TODO: change api key,
*/