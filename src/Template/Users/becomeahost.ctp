<?= $this->Html->css('signup.css') ?>
<?= $this->Html->script('signup.js') ?>
    
<h2><?= __("Hello - glad you're here! You'd like to rent your desk? Great! Let's do it!") ?></h2>

<div class="signup flexbox">
    <div class="description">
    <?= __("Your profile stores your business data and represents your Yellow Desks. You can use it to advertise your Yellow Desks and manage your earnings.") ?>
    </div>
    <div class="signupform">
        <form name="form1" method="post">
            <div id="step1">
                <h3>Your personal data</h3>
                <table id="table1">
                    <tr>
                        <td><label for="name">Company Name</label></td>
                        <td><label for="firstname">First Name</label></td>
                    </tr>
                    <tr>
                        <td><input type="text" name="name" id="name" placeholder="Company Name" value="<?= @$data['name'] ?>"  />
                        
                        <td><input type="text" name="firstname" placeholder="First Name" value="<?= @$data['firstname'] ?>" /></td>
                    </tr>
                    <tr class="errorline">
                        <td>
                            <span class="check name"><?= __("Please note that Yellow Desks is a B2B service.") ?></span></td>
                        </td>
                    </tr>
                    <tr class="space">
                        <td><label for="lastname">Last Name</label></td>
                        <td><label for="email">E-Mail</label></td>
                    </tr>
                    
                    <tr>
                        <td><input type="text" name="lastname" placeholder="Last Name" value="<?= @$data['lastname'] ?>" /></td>
                        <td><input type="text" name="email" placeholder="E-Mail" value="<?= @$data['email'] ?>" /></td>
                    </tr>
                    <tr class="space">
                        <td><label for="address"><?= __("Billing Address") ?></label></td>
                    </tr>
                    <tr class="inputs">
                        <td colspan="2"><input type="text" name="address" id="address" placeholder="Billing Address" value="<?= @$data['address'] ?>" /></td>
                    </tr>
                    
                    <tr class="space">
                        <td><label for="postal_code">Postal Code</label></td>
                        <td><label for="city">City</label></td>
                    </tr>
                    <tr class="inputs">
                        <td><input type="text" name="postal_code" id="postal_code" placeholder="Postal Code" value="<?= @$data['postal_code'] ?>" /></td>
                        <td><input type="text" name="city" id="city" placeholder="City" value="<?= @$data['city'] ?>" /></td>
                    </tr>

                    <tr class="space">
                        <td colspan="2"><label for="password">Password</label></td>
                    </tr>
                    <tr class="inputs">
                        <td colspan="2"><input type="password" name="password" id="password" placeholder="Password" value="<?= @$data['password'] ?>" /></td>
                    </tr>
                    <tr class="errorline">
                        <td colspan="2">
                            <span class="check password"><?= __("Please make sure your password is at least 8 characters long") ?></span></td>
                        </td>
                    </tr>

                    

                    <tr class="inputs">
                        
                        <td><h3>Your Yellowdesk(s)</h3><br /><label for="desks">Number of Desks</label></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><input type="text" name="desks" id="desks" placeholder="Number of Desks: 2" value="<?= @$data['desks'] ?>"  />
                        <td></td>
                    </tr>
                    <tr class="space">
                        <td colspan="2"><label for="included">Title</label></td>
                    </tr>
                    <tr class="inputs">
                        <td colspan="2"><input type="text" name="title" id="title" placeholder="Beatuiful office space downtown" value="<?= @$data['title'] ?>" /></td>
                    </tr>
                    <tr class="space">
                        <td colspan="2"><label for="included">Included  (for office space minimum requirements are: WiFi, desk and chair)</label></td>
                    </tr>
                    <tr class="inputs">
                        <td colspan="2"><input type="text" name="details" id="details" placeholder="Included: Coffee, B/W A4 Printer, WiFi, Telephone Room." value="<?= @$data['details'] ?>" /></td>
                    </tr>
                    <tr class="inputs">
                        <td colspan="2"><label for="extras">Excluded</label></td>
                    </tr>
                    <tr class="inputs">
                        <td colspan="2"><input type="text" name="extras" id="extras" placeholder="Excluded: Parking lot, High-Speed Wifi, Conference Room." value="<?= @$data['extras'] ?>" /></td>
                    </tr>
                    <tr class="space">
                        <td><label for="ydaddress"><?= __("Please drag the marker to the excact location of your Yellowdesk(s)") ?></label></td>
                    </tr>
                    <tr class="inputs">
                        <td colspan="2"><input type="text" id="pac-input" /></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div class="maptarget"></div>
                            <div id="map" style="width: 100%; height: 250px;"></div>
                        </td>
                    </tr>
                    
                    
                    <tr class="space">
                        <td><input type="checkbox" value="yes" name="termsandconditions" /><label for="termsandconditions"><?= __("I aggree to <a target='_blank' href={0}>Terms & Conditions</a>", $this->Url->build(["controller" => "termsandconditions", "action" => "index"])); ?></label></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="right">
                        <input readonly="readonly" type="hidden" name="lat" id="lat" placeholder="Lat" value="<?= @$data['lat'] ?>" />
                        <input readonly="readonly" type="hidden" name="lng" id="lng" placeholder="Lng" value="<?= @$data['lng'] ?>" />
                        <input type="submit" id="finish" value="<?= __("Finish") ?>" /></td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
</div>

<?= $this->Html->script('mapmarker.js') ?>

<script>
    $(window).on('positionchanged', function (e) {
        console.log("event " + e.state.lat + "/" + e.state.lng);
        $("#lat").val(e.state.lat);
        $("#lng").val(e.state.lng);
    });
</script>