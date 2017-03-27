var map;
var marker = null;



// salzburg, default
var position = {lat: 47.80097678080353, lng: 13.044660806655884 };

function initMap() {
    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 12,
        center: position
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
            setPosition( marker.position.lat(), marker.position.lng());
        }
    );
}

function setPosition(lat, lng) {
    this.position = { lat: lat, lng: lng };

    var evt = $.Event('positionchanged');
    evt.state = position;
    $(window).trigger(evt);
}