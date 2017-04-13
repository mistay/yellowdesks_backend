<?= $this->Html->css('../3rdparty/lightslider/css/lightslider.css') ?>
<style>
    .demo {
        width: 100%;
    }
</style>
<?= $this->Html->script('../3rdparty/lightslider/js/lightslider.js'); ?>

<?= $this->Html->script('../3rdparty/pickadate/picker.js'); ?>
<?= $this->Html->script('../3rdparty/pickadate/picker.date.js'); ?>
<?= $this->Html->script('../3rdparty/pickadate/legacy.js'); ?>

<?= $this->Html->css('../3rdparty/pickadate/themes/default.css') ?>
<?= $this->Html->css('../3rdparty/pickadate/themes/default.date.css') ?>

<h2>Details</h2>

<div class="demo">
    <ul class="lightSlider">
        <?php foreach ($row -> pictures as $picture) { ?>
            <li><img src="<?= $this->Url->build(["controller" => "pictures", "action" => "get", $picture->id, "resolution" => "800x300", "crop" => true]); ?>"/></li>
        <?php } ?>
    </ul>
</div>
<script> $(".lightSlider").lightSlider({
            gallery: false,
            item: 1,
            auto: true,
            pause: 5000,
            verticalHeight: 100,
            keyPress: true,
            /* loop: true, prevents video from beeing played properly */
        }); 
</script>

<?= $row -> nickname ?>
<?= $row -> details ?>

<?= $row -> extras ?>

<?= $row -> open_monday ?>
<?= $row -> open_tuesday ?>
<?= $row -> open_wednesday ?>
<?= $row -> open_thursday ?>
<?= $row -> open_friday ?>
<?= $row -> open_saturday ?>
<?= $row -> open_sunday ?>

<?= $row -> price_1day ?>
<?= $row -> price_10days ?>
<?= $row -> price_1month ?>
<?= $row -> price_6months ?>

<input type="text" class="datepicker startdate" />
<input type="text" class="datepicker enddate" />

<div class="pricecalculation"></div>

<form action="https://www.paypal.com/cgi-bin/webscr" method="post" style="margin-bottom: 0px; height:0px">
    <input type="hidden" name="cmd" value="_xclick">
    <input type="hidden" name="business" value="hello@yellowdesks.com">
    <input type="hidden" name="lc" value="AT">
    <input type="hidden" id="item_name" name="item_name" value="">
    <input type="hidden" id="item_number" name="item_number" value="">
    <input type="hidden" id="custom" name="custom" value="">
    <input type="hidden" id="amount" name="amount" value="25">
    <input type="hidden" id="currency_code" name="currency_code" value="EUR">
    <input type="hidden" name="button_subtype" value="services">
    <input type="hidden" name="no_note" value="0">
    <input type="hidden" name="no_shipping" value="1">
    <input type="hidden" id="tax_rate" name="tax_rate" value="0.000">
    <input type="hidden" id="returnurl" name="return" value="https://coworkingspacesalzburg.at/paypals/success">
    <input type="hidden" name="bn" value="PP-BuyNowBF:btn_paynow_SM.gif:NonHostedGuest">
    <a class="buynow" id="buynow1" onclick="this.parentElement.submit()">
    buy now 
    </a>
    <img alt="" border="0" src="https://www.paypal.com/de_DE/i/scr/pixel.gif" width="1" height="1">
</form>

<script>
    var $input = $('.datepicker').pickadate();

    $(".datepicker").on("change paste keyup", function() {

        var startdate = $(".startdate").val();
        var enddate = $(".enddate").val();
        
        if ($(".startdate").val() != "" && $(".enddate").val() != "") {
            $.ajax({
                url: "<?= $this->Url->build(["controller" => "bookings", "action" => "prepare", $row -> id]); ?>/" + startdate + "/" + enddate + "/true",
                context: document.body,
                dataType: "json",
            }).done(function(data) {
                var id = 0;
                var nickname = "";
                var begin = 0;
                var end = 0;
                
                var i = 0;
                for (i=0; i<Object.keys(data).length; i++) {
                    if ($.isNumeric(Object.keys(data)[i])) {
                        // found
                        id = Object.keys(data)[i];
                        nickname = Object.values(data)[i].nickname;
                        begin = Object.values(data)[i].begin;
                        end = Object.values(data)[i].end;
                        break;
                    }
                }

                if (nickname != "" && begin != "" && end != "") {
                    $(".pricecalculation").html(data.num_workingdays + " Workingday(s): " + data.total + "EUR");
                    
                    $("#item_name").val("Yellosdesks from " + begin + " to " + end + " at host " + nickname);
                    $("#amount").val(data.total);
                    $("#custom").val("["+id+"]");
                }
            });
        }
    });
</script>