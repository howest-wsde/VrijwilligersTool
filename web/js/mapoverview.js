"use strict";

var map, markers = [];
function initMap() {
    map = new google.maps.Map(document.getElementById('map-all'), {
      center: {lat: 50.978231, lng: 3.197955},
      zoom: 8
    });

    $.getJSON( RV_GLOBALS["locations"], function( data ) {
        $.each( data, function( nr, item ) {
            markers[markers.length] = new google.maps.Marker({
              position: {lat: item.lat*1, lng: item.lng*1},
              map: map,
            });
        });
    });
}


$(document).ready(function(){
    initMap();
});
