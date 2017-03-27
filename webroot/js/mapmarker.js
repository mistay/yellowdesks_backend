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
        position: {lat: this.position.lat, lng: this.position.lng},
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

$( document ).ready(function() {
    $("#address").bind("input", function() {
    // todo: escape properly
    $.ajax({
        url: "https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyD4HecLgzMZ6sK8fYSracEULluXdujR8BU&address=" + $(this).val(),
        }).done(function(result) {
            console.log(result.results[0].geometry.location.lat + "/" + result.results[0].geometry.location.lng);
            setPosition (result.results[0].geometry.location.lat, result.results[0].geometry.location.lng);
        });
    });
});