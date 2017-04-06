<?= $this->Html->css('mapmarker.css') ?>

<script>
    var host = <?= json_encode([
        "lat" => $row["lat"],
        "lng" => $row["lng"],
        ]); 
    ?>;
</script>

<div class="ajaxresponse"></div>

<p>
<?= __("Please specify your accurate position by moving the marker below (your Pin will be blurry on the Yellowdesks start page). Then, use the 'Save Position' button.") ?>
</p>
<input type="button" value="<?= __('Save Position') ?>" id="save" />
<br />
<br />
<input type="text" id="pac-input" />
<div id="map" width="100%" style="height: 500px;"></div>

<script type="text/javascript">
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

<?= $this->Html->script('mapmarker.js') ?>
<script>
    setPosition(host.lat, host.lng);
</script>