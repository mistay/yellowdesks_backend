var map;
var marker = null;

// salzburg, default
var lat = 47.80097678080353;
var lng = 13.044660806655884;

function initMap() {
    var uluru = {lat: lat, lng: lng};
    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 12,
        center: uluru
    });

    makeMarker();
}


function makeMarker() {
    marker = new google.maps.Marker({
        position: {lat: lat, lng: lng},
        map: map,
        icon: '../img/yellowdot.png',
        draggable:true,
    });

    marker.addListener('dragend', 
        function markerdragged() {
            var evt = $.Event('positionchanged');
            evt.state = { lat: marker.position.lat(), lng: marker.position.lng() };

            $(window).trigger(evt);
        }
    );
}

function setPosition(lat, lng) {
    this.lat = lat;
    this.lng = lng;
}