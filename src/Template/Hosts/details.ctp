<?= $this->Html->css('../3rdparty/lightslider/css/lightslider.css') ?>
<style>
    .demo {
        width: 100%;
    }

    .buynow {
        background: black;
        color: yellow;
        padding: 7px;
        display: none;
        cursor: pointer;
    }
    .buynow:hover {
        color: white;
    }

    input[type="text"].datepicker {
        width: 25%;
        font-size: 24px;
        min-width: 300px;
    }
    label {
        display: block;
        margin-top: 5px;
        margin-bottom: 0px;
    }

    .dateboxes {
        display: flex;
        flex-direction: row;
        width: 100%;
        flex-wrap: wrap;
    }
</style>
<?= $this->Html->script('../3rdparty/lightslider/js/lightslider.js'); ?>

<?= $this->Html->script('../3rdparty/pickadate/picker.js'); ?>
<?= $this->Html->script('../3rdparty/pickadate/picker.date.js'); ?>
<?= $this->Html->script('../3rdparty/pickadate/legacy.js'); ?>

<?= $this->Html->css('../3rdparty/pickadate/themes/default.css') ?>
<?= $this->Html->css('../3rdparty/pickadate/themes/default.date.css') ?>

<h3><?= __("Host") . ": " . $row -> nickname ?></h3>
<h3><?= $row -> title ?></h3>

<div class="demo">
    <ul class="lightSlider">
        <?php foreach ($row -> pictures as $picture) { ?>
            <li><img src="<?= $this->Url->build(["controller" => "pictures", "action" => "get", $picture->id, "resolution" => "800x300", "crop" => true]); ?>"/></li>
        <?php } ?>
    </ul>
</div>
<script> 
    $(".lightSlider").lightSlider({
        gallery: false,
        item: 1,
        auto: true,
        pause: 5000,
        verticalHeight: 100,
        keyPress: true,
        /* loop: true, prevents video from beeing played properly */
    }); 
</script>

<strong><?= __("Included") ?></strong> <?= $row -> details ?><br />
<strong><?= __("Excluded") ?></strong> <?= $row -> extras ?><br />
<br />
    
<strong><?= __("Opening hours") ?></strong> 
<?php
if ($row -> open_monday_from == null
    &&
    $row -> open_tuesday_from == null
    &&
    $row -> open_wednesday_from == null
    &&
    $row -> open_thursday_from == null
    &&
    $row -> open_friday_from == null
    &&
    $row -> open_saturday_from == null
    &&
    $row -> open_sunday_from == null
    )
        echo "n/a";
?>
<br />

<?php if ($row -> open_monday_from != null) { ?>
    <?= __("Monday") . ": " . $row -> open_monday_from -> i18nFormat('HH:mm') . " - " . $row -> open_monday_till -> i18nFormat('HH:mm') ?><br />
<?php } elseif ($row -> open_tuesday_from != null) { ?>
    <?= __("Tuesday") . ": " . $row -> open_tuesday_from -> i18nFormat('HH:mm') . " - " . $row -> open_tuesday_till -> i18nFormat('HH:mm') ?><br />
<?php } elseif ($row -> open_wednesday_from != null) { ?>
    <?= __("Wednesday") . ": " . $row -> open_wednesday_from -> i18nFormat('HH:mm') . " - " . $row -> open_wednesday_till -> i18nFormat('HH:mm') ?><br />
<?php } elseif ($row -> open_thursday_from != null) { ?>
    <?= __("Thursday") . ": " . $row -> open_thursday_from -> i18nFormat('HH:mm') . " - " . $row -> open_thursday_till -> i18nFormat('HH:mm') ?><br />
<?php } elseif ($row -> open_friday_from != null) { ?>
    <?= __("Friday") . ": " . $row -> open_friday_from -> i18nFormat('HH:mm') . " - " . $row -> open_friday_till -> i18nFormat('HH:mm') ?><br />
<?php } elseif ($row -> open_saturday_from != null) { ?>
    <?= __("Saturday") . ": " . $row -> open_saturday_from -> i18nFormat('HH:mm') . " - " . $row -> open_saturday_till -> i18nFormat('HH:mm') ?><br />
<?php } elseif ($row -> open_sunday_from != null) { ?>
    <?= __("Sunday") . ": " . $row -> open_sunday_from -> i18nFormat('HH:mm') . " - " . $row -> open_sunday_till -> i18nFormat('HH:mm') ?><br />
<?php } ?>

<?= $row -> price_1day > 0 ? $row -> price_1day. " EUR" : "" ?>
<?= $row -> price_10days > 0 ? $row -> price_10days. " EUR" : "" ?>
<?= $row -> price_1month > 0 ? $row -> price_1month. " EUR" : "" ?>
<?= $row -> price_6months > 0 ? $row -> price_6months . " EUR" : "" ?>
<br />

<div class="dateboxes">
    <div>
        <label for="startdate"><?= __("Start Date") ?></label>
        <input type="text" id="startdate" class="datepicker startdate" placeholder="<?= __("Start Date") ?>" value="<?= date("Y-m-d"); ?>" />
    </div>

    <div>
        <label for="enddate"><?= __("End Date") ?></label>
        <input type="text" id="enddate" class="datepicker enddate" placeholder="<?= __("End Date") ?>" value="<?= date("Y-m-d"); ?>" />
    </div>
</div>
<br />

<div class="pricecalculation"></div>
<br />
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
    <input type="hidden" value="2" name="rm">
    <input type="hidden" id="tax_rate" name="tax_rate" value="0.000">
    <input type="hidden" id="return" name="return" value="https://www.yellowdesks.com/paypals/success">
    <input type="hidden" name="bn" value="PP-BuyNowBF:btn_paynow_SM.gif:NonHostedGuest">
    <a class="buynow" id="buynow1" onclick="this.parentElement.submit()">
    buy now 
    </a>
    <img alt="" border="0" src="https://www.paypal.com/de_DE/i/scr/pixel.gif" width="1" height="1">
</form>

<script>
    var $input = $('.datepicker').pickadate();

    $(".datepicker").on("change paste keyup", function() {
        pricecalc();
    });

    $( document ).ready(function() {
        pricecalc();
    });

    function pricecalc() {
        var startdate = $(".startdate").val();
        var enddate = $(".enddate").val();

        var loggedinuser = <?php echo $user == null ? "false" : "true" ?>;
        

        if ($(".startdate").val() != "" && $(".enddate").val() != "") {
            $.ajax({
                url: "<?= $this->Url->build(["controller" => "bookings", "action" => "prepare", $row -> id]); ?>/" + startdate + "/" + enddate + "/" + (loggedinuser ? "true" : ""),
                context: document.body,
                dataType: "json",
            }).done(function(data) {
                var id = 0;
                var nickname = "";
                var begin = 0;
                var end = 0;
                
                if (loggedinuser) {
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
                        $(".buynow").show();

                        $("#item_name").val("Yellowsdesks from " + begin + " to " + end + " at host " + nickname);
                        $("#amount").val(data.total);
                        $("#custom").val("["+id+"]");
                    }
                } else {
                    // not logged in

                    var text = data.num_workingdays + " Workingday(s): " + data.total + "EUR";
                    text += ". please login to book."
                    $(".pricecalculation").html(text);
                }
            });
        } else {
            $(".pricecalculation").html( "Please specify start- and end date to request quote." );
            $(".buynow").hide();
        }
    }
</script>