


<script>
    console.log("hello");
    var hosts = <?= json_encode($rows); ?>;
    console.log(hosts);

    
</script>
    
    <div id="map" style="width:500px; height:500px"></div>
    <script>
      function initMap() {
        var uluru = {lat: 47.806021, lng: 13.050602000000026};
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 12,
          center: uluru
        });

        for (i=0; i<hosts.length; i++) {
            console.log(hosts[i].lat);
            new google.maps.Marker({
                position: {lat: hosts[i].lat, lng: hosts[i].lng},
                map: map
            });
        }

      }
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD4HecLgzMZ6sK8fYSracEULluXdujR8BU&callback=initMap">
    </script>

    asdfgs
