


<script>
    // todo: security: remove hosts.name!!! sonst kann coworker dien firmennamen schon vorab lesen
    // todo: auch felder wie webseite, e-mail, ... entfernen
    var hosts = <?= json_encode($rows); ?>;
    //console.log(hosts[0]);
</script>


<div id="map" width="100%" style="height: 500px;"></div>


<script>


    


    var map;

    function bla(host) {

        var str  = '<div class="infobox">'+
            '</div>'+
            '<h1 id="firstHeading" class="firstHeading">host.nickname</h1>'+
            '<div id="bodyContent">'+
            '<p><b>host.title</b><br />' +
            '<b>Included: </b>host.details<br />'+
            '<b>Extras: </b>host.extras<br />'+
            '<b>Open Monday:</b>host.open_monday_from - host.open_monday_till<br />'+
            '<b>Open Tuesday:</b>host.open_tuesday_from - host.open_tuesday_till<br />'+
            '<b>Open Wednesday:</b>host.open_wednesday_from - host.open_wednesday_till<br />'+
            '<b>Open Thursday:</b>host.open_thursday_from - host.open_thursday_till<br />'+
            '<b>Open Friday:</b>host.open_friday_from - host.open_friday_till<br />'+
            '<b>Open Saturday:</b>host.open_saturday_from - host.open_saturday_till<br />'+
            '<b>Open Sunday:</b>host.open_sunday_from - host.open_sunday_till<br />'+
            '<b>Open Price 1day:</b>host.price_1day<br />'+
            '<b>Open Price 10days:</b>host.price_10days<br />'+
            '<b>Open Price 1 month:</b>host.price_1month<br />'+
            '<b>Open Price 6 months:</b>host.price_6months<br />'+
            '</div>';

        str = str.replace("host.nickname", host.nickname);
        str = str.replace("host.title", host.title);
        str = str.replace("host.details", host.details);
        str = str.replace("host.extras", host.extras);
        str = str.replace("host.picture_id", host.picture_id);
        str = str.replace("host.video_id", host.video_id);
        str = str.replace("host.open_247fixworkers", host.open_247fixworkers);
        str = str.replace("host.open_monday_from", host.open_monday_from);
        str = str.replace("host.open_monday_till", host.open_monday_till);
        str = str.replace("host.open_tuesday_from", host.open_tuesday_from);
        str = str.replace("host.open_tuesday_till", host.open_tuesday_till);
        str = str.replace("host.open_wednesday_from", host.open_wednesday_from);
        str = str.replace("host.open_wednesday_till", host.open_wednesday_tkll);
        str = str.replace("host.open_thursday_from", host.open_thursday_from);
        str = str.replace("host.open_thursday_till", host.open_thursday_till);
        str = str.replace("host.open_friday_from", host.open_friday_from);
        str = str.replace("host.open_friday_till", host.open_friday_till);
        str = str.replace("host.open_saturday_from", host.open_saturday_from);
        str = str.replace("host.open_saturday_till", host.open_saturday_from);
        str = str.replace("host.open_sunday_from", host.open_sunday_from);
        str = str.replace("host.open_sunday_till", host.open_sunday_till);
        str = str.replace("host.price_1day", host.price_1day);
        str = str.replace("host.price_10days", host.price_10days);
        str = str.replace("host.price_1month", host.price_1month);
        str = str.replace("host.price_6months", host.price_6months);
        
        return str;
    }

    String.prototype.format = function()
    {
        var content = this;
        for (var i=0; i < arguments.length; i++)
        {
                var replacement = '{' + i + '}';
                content = content.replace(replacement, arguments[i]);  
        }
        return content;
    };


    function markerclick(event) {
        var infowindow = new google.maps.InfoWindow({
            content: bla(this.host),
        });
        infowindow.open(map, this);
    }

    function initMap() {
        var image = '../img/yellowdot.png';
        var uluru = {lat: 47.806021, lng: 13.050602000000026};
            map = new google.maps.Map(document.getElementById('map'), {
            zoom: 12,
            center: uluru
            });

        for (i=0; i<hosts.length; i++) {
            

        marker = new google.maps.Marker({
            position: {lat: 47.806021, lng: 13.050602000000026},
            map: map,
            icon: image,
            host: hosts[i],
            });
            
            

        }
    }
</script>

<?= __("Please specify your accurate position by moving the marker above. You wonder why it does not appear like that in the overview map? No drama - we did it on purpose. Your coworker will receive the exact location just after booking."); ?>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD4HecLgzMZ6sK8fYSracEULluXdujR8BU&callback=initMap"></script>
