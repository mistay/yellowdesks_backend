<?php
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Datasource\ConnectionManager;
use Cake\Error\Debugger;
use Cake\Network\Exception\NotFoundException;

$this->layout = false;
?>
<!DOCTYPE html>
<html prefix="og: http://ogp.me/ns#">
    <head>
        <meta property="og:title" content="Yellowdesks" />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="https://www.yellowdesks.com" />
        <meta property="og:image" content="https://www.yellowdesks.com/img/opengraph_image_yellowdesks.jpg" />
        <meta property="og:app_id" content="349857342038820" />

	<link rel="alternate" href="https://www.yellowdesks.com/" hreflang="en" />

        <!-- Piwik -->
        <script type="text/javascript">
        var _paq = _paq || [];
        /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
        _paq.push(['trackPageView']);
        _paq.push(['enableLinkTracking']);
        (function() {
            var u="https://piwik.langhofer.at/";
            _paq.push(['setTrackerUrl', u+'piwik.php']);
            _paq.push(['setSiteId', '1']);
            var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
            g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
        })();
        </script>
        <!-- End Piwik Code -->

        <meta charset="utf-8">
        <title>yellowdesks</title>
        <meta name="description" content="">
        <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
     <!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
        <link rel="stylesheet" href="css/main.css">
        <link rel="stylesheet" href="css/jquery.steps.css">
        <link rel="stylesheet" href="fonts/eraser/stylesheet.css">
        <link rel="stylesheet" href="fonts/din1451/stylesheet.css">
        
        <script>
            <?php
                // security: only expose public fields
                $rets = [];
                foreach ($rows as $row) {
                    $ret = new stdClass();

                    if (strpos($row->nickname, "test") === 0 && ($loggedinuser == null || $loggedinuser->role != "ADMIN"))
                        continue;
                    $ret-> id = $row -> id;
                    $ret-> nickname = $row -> nickname;
                    $ret-> title = $row -> title;
                    $ret-> details = $row -> details;
                    $ret-> extras = $row -> extras;
                    $ret-> lat_loose = $row -> lat_loose;
                    $ret-> lng_loose = $row -> lng_loose;
                    $ret-> open_monday_from = $row -> open_monday_from == null ? null : date("H:i", strtotime($row -> open_monday_from));
                    $ret-> open_monday_till = $row -> open_monday_till == null ? null : date("H:i", strtotime($row -> open_monday_till));
                    $ret-> open_tuesday_from = $row -> open_tuesday_from == null ? null : date("H:i", strtotime($row -> open_tuesday_from));
                    $ret-> open_tuesday_till = $row -> open_tuesday_till == null ? null : date("H:i", strtotime($row -> open_tuesday_till));
                    $ret-> open_wednesday_from = $row -> open_wednesday_from == null ? null : date("H:i", strtotime($row -> open_wednesday_from));
                    $ret-> open_wednesday_till = $row -> open_wednesday_till == null ? null : date("H:i", strtotime($row -> open_wednesday_till));
                    $ret-> open_thursday_from = $row -> open_thursday_from == null ? null : date("H:i", strtotime($row -> open_thursday_from));
                    $ret-> open_thursday_till = $row -> open_thursday_till == null ? null : date("H:i", strtotime($row -> open_thursday_till));
                    $ret-> open_friday_from = $row -> open_friday_from == null ? null : date("H:i", strtotime($row -> open_friday_from));
                    $ret-> open_friday_till = $row -> open_friday_till == null ? null : date("H:i", strtotime($row -> open_friday_till));
                    $ret-> open_saturday_from = $row -> open_saturday_from == null ? null : date("H:i", strtotime($row -> open_saturday_from));
                    $ret-> open_saturday_till = $row -> open_saturday_till == null ? null : date("H:i", strtotime($row -> open_saturday_till));
                    $ret-> open_sunday_from = $row -> open_sunday_from == null ? null : date("H:i", strtotime($row -> open_sunday_from));
                    $ret-> open_sunday_till = $row -> open_sunday_till == null ? null : date("H:i", strtotime($row -> open_sunday_till));
                    $ret-> price_1day = $row -> price_1day;
                    $ret-> price_10days = $row -> price_10days;
                    $ret-> price_1month = $row -> price_1month;
                    $ret-> price_6months = $row -> price_6months;
                    array_push($rets, $ret);
                }
            ?>
            var hosts = <?= json_encode($rets); ?>;
        </script>

        <script>
            var map;

            function getinfoboxcontent(host) {
                var str  = '<div class="infobox">'+
                    '</div>'+
                    '<h1 class="firstHeading">host.nickname</h1>'+
                    '<div class="bodyContent">'+
                    '<p><a href="https://play.google.com/store/apps/details?id=com.yellowdesks.android">Book on Android App</a></p>' +
                    '<p><b>host.title</b><br />' +
                    '<b>Included: </b>host.details<br />'+
                    '<b>Extras: </b>host.extras<br />'+
                    'open_monday'+
                    'open_tuesday'+
                    'open_wednesday'+
                    'open_thursday'+
                    'open_friday'+
                    'open_saturday'+
                    'open_sunday'+
                    'price_1day'+
                    'price_10days'+
                    'price_1month'+
                    'price_6months'+
                    '</div>';

                str = str.replace("host.nickname", host.nickname);
                str = str.replace("host.title", host.title);
                str = str.replace("host.details", host.details);
                str = str.replace("host.extras", host.extras);
                str = str.replace("host.picture_id", host.picture_id);
                str = str.replace("host.video_id", host.video_id);
                str = str.replace("host.open_247fixworkers", host.open_247fixworkers);
                str = str.replace("open_monday", host.open_monday_from == null ? "" : '<b>Open Monday</b> ' + host.open_monday_from + '-' + host.open_monday_till + '<br />');
                str = str.replace("open_tuesday", host.open_tuesday_from == null ? "" : '<b>Open Tuesday</b> ' + host.open_tuesday_from + '-' + host.open_tuesday_till + '<br />');
                str = str.replace("open_wednesday", host.open_wednesday_from == null ? "" : '<b>Open Wednesday</b> ' + host.open_wednesday_from + '-' + host.open_wednesday_till + '<br />');
                str = str.replace("open_thursday", host.open_thursday_from == null ? "" : '<b>Open Thursday</b> ' + host.open_thursday_from + '-' + host.open_thursday_till + '<br />');
                str = str.replace("open_friday", host.open_friday_from == null ? "" : '<b>Open Friday</b> ' + host.open_friday_from + '-' + host.open_friday_till + '<br />');
                str = str.replace("open_saturday", host.open_saturday_from == null ? "" : '<b>Open saturday</b> ' + host.open_saturday_from + '-' + host.open_saturday_till + '<br />');
                str = str.replace("open_sunday", host.open_sunday_from == null ? "" : '<b>Open sunday</b> ' + host.open_sunday_from + '-' + host.open_sunday_till + '<br />');

                str = str.replace("price_1day", host.price_1day == null ? "" : '<b>Price 1 Day</b> ' + host.price_1day + '<br />');
                str = str.replace("price_10days", host.price_10days == null ? "" : '<b>Price 10 Days</b> ' + host.price_10days + '<br />');
                str = str.replace("price_1month", host.price_1month == null ? "" : '<b>Price 1 Month</b> ' + host.price_1month + '<br />');
                str = str.replace("price_6months", host.price_6months == null ? "" : '<b>Price 6 Months</b> ' + host.price_6months + '<br />');

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
                    content: getinfoboxcontent(this.host),
                });
                /*gerd infowindow.close();*/
                $("#home-logo").addClass("smalllogo");
                infowindow.open(map, this);
            }

            function initMap() {
                var image = "<?= $this->Url->build('/img/yellowdot.png', true); ?>";
                var uluru = {lat: 47.806021, lng: 13.050602000000026};
                    map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 10,
                    center: uluru
                    });

                for (i=0; i<hosts.length; i++) {
                    
                    marker = new google.maps.Marker({
                        position: {lat: hosts[i].lat_loose, lng: hosts[i].lng_loose},
                        map: map,
                        icon: image,
                        host: hosts[i],
                        });
                    marker.addListener('click', markerclick);
                }
            }
        </script>

        <?php
            $url = $this->Url->build('/favicon.jpg', true);
        ?>
        
        <link rel="icon" type="image/jpeg" href="<?= $url ?>" />
        <script src="js/jquery-1.9.1.min.js"></script>
        
        <style>

            html {
                height: 100%;
                width: 100%;
            }
            body {
                /* background: url("img/eva.jpg") no-repeat center center fixed;
                background-size: cover; */
                padding: 0px;
                margin: 0px;
                bottom: 0px;
                font-family: din;
                height: 100%;
                width: 100%;
            }
            
            .footer {
                position: absolute;
                bottom: 10px;
                width: 100%;
                text-align: center;
                color: white;
                z-index: 100;
            }

            .footer a {
                color: black;
                text-decoration: none;
            }
            
            .coworkingsalzburg {
            }
            
            .yellowdesks {
                background-color: #f3ed3d;
                font-size: 55px;
                margin-left: 50px;
                font-family: eraserregular;
                display: block;
                margin-bottom: 12px;
                padding: 6px;
            }
            .yellowlinks { 
            display: block;
                margin-bottom: 6px;
            }
            .yellowlinks span {
            padding: 6px;
                display: inline-block;
            }
            
            @media (max-width: 600px) {
                .yellowdesks {
                    font-size: 30px;
                    margin-left: 5px;
                }
            }
            @media (max-device-width: 600px) {
                .yellowdesks {
                    font-size: 100px;
                    margin-left: 5px;
                }
            }
            
            .findandrent {
                font-size: 20px;
                background-color: #f3ed3d;
                margin-left: 50px;
            }
            @media (max-width: 600px) {
                .findandrent {
                    font-size: 15px;
                    margin-left: 5px;
                }
            }
            @media (max-device-width: 600px) {
                .findandrent {
                    font-size: 40px;
                    margin-left: 5px;
                }
            }

            .content {
              /*  padding-top: 25%;
                z-index: 100;
                position: absolute;*/
                z-index: 100;
                position: absolute;
                bottom: 40px;
                left: 0px;
                
            }
            
            .menu {
                padding-top: 10px;
                padding-right: 10px;
                display: flex;
                flex-direction: row;
                justify-content: flex-end;
                position: absolute;
                right: 0;
                z-index: 100;
            }
            @media (max-width: 600px) {
                .menu {
                    justify-content: flex-start;
                    width: 100%;
                }
            }
            @media (max-device-width: 600px) {
                .menu {
                    font-size: 30px;
                    width: 100%;
                    padding-right: 0px;
                }
                .menu a {
                    flex-grow: 1;
                }
            }
            
            .menu a {
                background-color: white;
                min-width: 140px;
                display: inline-block;
                margin: 5px;
                padding: 5px;
                padding-left: 20px;
                text-decoration: none;
                color: black;
            }

            #map {
                height: 100%;
                width: 100%;
            }

            .menu a.facebooklogo, .menu a.androidlogo, .menu a.questionmark {
                min-width: auto;
                padding-left: 10px;
                padding-right: 10px;
            }
            .androidlogo img, .facebooklogo img, .questionmark img {
                height: 20px;
            }
        </style>
        
        <script>
            $("#finish").onclick = function() {
                alert("f90");
            }
        </script>
    </head>
    <body>

        <div class="menu">
            <?php
            
            $urlroot = $this->Url->build("/");

            $urlprofile = $this->Url->build([
                    "controller" => "users",
                    "action" => "welcome",
                ]);

            $urlbecomeahost = $this->Url->build([
                    "controller" => "users",
                    "action" => "becomeahost",
                ]);

            $urlregister = $this->Url->build([
                    "controller" => "users",
                    "action" => "signup",
                ]);
            
            if ($loggedinuser == null) {
                $url = $this->Url->build([
                    "controller" => "users",
                    "action" => "login",
                ]);
                $loginlogouttext = __("Login");
            } else {
                $url = $this->Url->build([
                    "controller" => "users",
                    "action" => "logout",
                ]);
                $loginlogouttext = __("Logout");
            }
            ?>

            <a class="questionmark" href="/faqs"><img src="<?= $urlroot ?>img/questionmark_bw_transparent.png" /></a>
            <a class="facebooklogo" target="_blank" href="https://www.facebook.com/yellowdesks/"><img src="<?= $urlroot ?>img/facebook_transparent.png" /></a>
            <a class="androidlogo" target="_blank" href="https://play.google.com/store/apps/details?id=com.yellowdesks.android"><img src="<?= $urlroot ?>img/android_logo_bw_transparent.png" /></a>
            
            <?php if ($loggedinuser == null) { ?>
                <a href="<?= $urlbecomeahost ?>">Become A Host</a>
                <a href="<?= $urlregister ?>">Sign Up</a>
            <?php } else { ?>
                <a href="<?= $urlprofile ?>"><?= __("Profile") ?></a>
            <?php } ?>

            <a href="<?= $url ?>"><?= $loginlogouttext ?></a>
        </div>
        
        
        <div class="content home-content" id="home-logo">
            <span class="yellowdesks">yellow desks</span>
            <div class="yellowlinks">
                <span class="findandrent"><strong>Find</strong> flexible work space near you</span>
            </div>
            <div class="yellowlinks">
                <span class="findandrent"><a href="https://www.yellowdesks.com/users/becomeahost" title="become a host" alt="become a host">&gt; &gt; <strong>Rent out</strong> work space</a></span>
            </div>
        </div>
        
        
        <div class="footer"><a href="http://coworkingsalzburg.com">by <span class="coworkingsalzburg"><strong>COWORKING</strong>SALZBURG</span></a></div>
    
        <div id="map"></div>
        
    </body>

    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD4HecLgzMZ6sK8fYSracEULluXdujR8BU&callback=initMap"></script>
</html>
