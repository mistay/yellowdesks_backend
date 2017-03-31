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

<p>
<?= __("Use this Address text field to search for your location"); ?>

<input type="text" id="address"></input>
</p>
<input type="button" value="save position" id="save" />
<br />
<br />
<div id="map" width="100%" style="height: 500px;"></div>


<?= $this->Html->script('mapmarker.js') ?>
<script type="text/javascript">
    $(".ajaxresponse").html();
    $(window).on('positionchanged', function (e) {
        console.log('position changed', e.state);
        // e.state.lat
        if (marker != null)
            marker.setPosition(position);

        if (map != null)
            map.setCenter(position);

        
    });
    if (host.lat != null && host.lng != null) {
        setPosition(host.lat, host.lng);
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


    $( document ).ready(function() {
        $("#save").click(function() {
            $.ajax({
                    url: "setposition",
                    data: position,
                    method: "post",
                    })
                    .done(function() {
                        $(".ajaxresponse").html("saved successfully");
                    })
                    .fail(function() {
                        $(".ajaxresponse").html("error saving position");
                    });
        });
    });
</script>
<?= __("Please specify your accurate position by moving the marker above. You wonder why it does not appear like that in the overview map? No drama - we did it on purpose. Your coworker will receive the exact location just after booking."); ?>
<br />


<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD4HecLgzMZ6sK8fYSracEULluXdujR8BU&callback=initMap"></script>