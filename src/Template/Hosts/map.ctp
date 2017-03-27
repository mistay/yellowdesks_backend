<script>
    // todo: security: remove hosts.name!!! sonst kann coworker dien firmennamen schon vorab lesen
    // todo: auch felder wie webseite, e-mail, ... entfernen
    var host = <?= json_encode([
        "lat" => $row["lat"],
        "lng" => $row["lng"],
        ]); 
    ?>;
    //console.log(host);
</script>

<div class="ajaxresponse"></div>
<div id="map" width="100%" style="height: 500px;"></div>


<?= $this->Html->script('mapmarker.js') ?>
<script type="text/javascript">
    $(".ajaxresponse").html();
    $(window).on('positionchanged', function (e) {
        console.log('position changed', e.state);
        $.ajax({
            url: "setposition",
            data: {lat: e.state.lat, lng: e.state.lng},
            method: "post",
            })
            .done(function() {
                $(".ajaxresponse").html("saved successfully");
            })
            .fail(function() {
                $(".ajaxresponse").html("error saving position");
            });
        
        
    });
    if (host.lat != null && host.lng != null) {
        setPosition(host.lat, host.lng);
    }
</script>

<?= __("Please specify your accurate position by moving the marker above. You wonder why it does not appear like that in the overview map? No drama - we did it on purpose. Your coworker will receive the exact location just after booking."); ?>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD4HecLgzMZ6sK8fYSracEULluXdujR8BU&callback=initMap"></script>