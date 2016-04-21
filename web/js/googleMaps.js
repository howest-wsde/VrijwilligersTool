var mapOptions = {
    zoom: 15,
    center: {lat: 50.948352, lng: 3.131108}
    //this default to a map of the centre of Roeselare

};
var map = new google.maps.Map($("#map-canvas")[0], mapOptions);
var address = $("#location").text();
var geocoder = new google.maps.Geocoder();

geocoder.geocode({'address': address}, function(results, status) {
    if (status === google.maps.GeocoderStatus.OK) {
        map.setCenter(results[0].geometry.location);
        var marker = new google.maps.Marker({
            map: map,
            position: results[0].geometry.location
        });
    } else {
        alert('Geocode was not successful for the following reason: ' + status);
    }
});

