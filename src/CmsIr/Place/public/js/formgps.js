$(function () {

    var map;
    var marker = null;

    function initialize()
    {

        if($('#latitude').val() && $('#longitude').val())
        {
            var myLatlng = new google.maps.LatLng($('#latitude').val(),$('#longitude').val());
            var mapOptions = {
                zoom: 8,
                center: myLatlng
            };
            map = new google.maps.Map(document.getElementById('map'), mapOptions);
            marker = new google.maps.Marker({ position: myLatlng, map: map});
        } else
        {
            var mapOptions = {
                zoom: 6,
                disableDefaultUI: false,
                center: new google.maps.LatLng(52.229675600000000000,19.012228900000047000),
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            map = new google.maps.Map(document.getElementById('map'), mapOptions);
        }

        google.maps.event.addListener(map, 'click', function(event) {
            $("#latitude").val(event.latLng.lat());
            $("#longitude").val(event.latLng.lng());
            displayLocation(event.latLng.lat(),event.latLng.lng());

            if (marker) { marker.setMap(null); }
            console.log(event.latLng);
            marker = new google.maps.Marker({ position: event.latLng, map: map});

        });

        google.maps.event.addListener(searchBox, 'places_changed', function() {
            var places = searchBox.getPlaces();
            if (places.length == 0) {
                return;
            }
            if (marker) { marker.setMap(null); }

            var bounds = new google.maps.LatLngBounds();
            for (var i = 0, place; place = places[i]; i++) {
                bounds.extend(place.geometry.location);
            }
            map.fitBounds(bounds);
        });

        google.maps.event.addListener(map, 'bounds_changed', function() {
            var bounds = map.getBounds();
            searchBox.setBounds(bounds);
        });

    }

    google.maps.event.addDomListener(window, 'load', initialize);

    var input = document.getElementById('search');
    var searchBox = new google.maps.places.SearchBox(input);
});

function displayLocation(latitude,longitude){
    var request = new XMLHttpRequest();

    var method = 'GET';
    var url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='+latitude+','+longitude+'&sensor=true';
    var async = true;

    request.open(method, url, async);
    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200)
        {
            var data = JSON.parse(request.responseText);
            var address = data.results[0];
            parseDataAddress(address);
        }
    };
    request.send();
}

function  parseDataAddress(address) {
    var addressComponents = address['address_components'];
    console.log(addressComponents);
    for (i = 0; i < addressComponents.length; i++)
    {
        var type = addressComponents[i]['types'][0];
        var value = addressComponents[i]['long_name'];
        switch(type)
        {
            case 'street_number':
                $('input[name="street_number"]').val(value);
            break;
            case 'route':
                $('input[name="street"]').val(value);
            break;
            case 'administrative_area_level_2':
                $('input[name="city"]').val(value);
            break;
            case 'administrative_area_level_1':
                $('input[name="region"]').val(value);
            break;
            case 'country':
                $('input[name="country"]').val(value);
            break;
        }
    }
}