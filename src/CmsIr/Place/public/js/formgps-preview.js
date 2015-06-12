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
        }
    }

    google.maps.event.addDomListener(window, 'load', initialize);
});