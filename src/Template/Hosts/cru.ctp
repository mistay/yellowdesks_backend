<h2><?php echo empty($row) ? "Add" : "Edit" ?> Host</h2>

<form name="form1" method="post">
    <table>
        <tr>
            <th>Name</th>
            <td><input type="text" name="name" placeholder="My Company Ltd" value='<?php echo @$row["name"] ?>' /></td>
        </tr>
        <tr>
            <th>Nickname</th>
            <td><input type="text" name="nickname" placeholder="John" value='<?php echo @$row["nickname"] ?>' /></td>
        </tr>
        <tr>
            <th>Username</th>
            <td><input type="text" readonly="readonly" name="username" placeholder="John" value='<?php echo @$row["username"] ?>' /></td>
        </tr>
        <tr>
            <th>Title</th>
            <td><input type="text" name="title" placeholder="Creative agency downtown Salzburg" value='<?php echo @$row["title"] ?>' /></td>
        </tr>
        <tr>
            <th>Included</th>
            <td><input type="text" name="details" placeholder="high speed wifi, business printer both a4 and a3, coffee" value='<?php echo @$row["details"] ?>' /></td>
        </tr>
        <tr>
            <th>Excluded</th>
            <td><input type="text" name="extras" placeholder="parking lot, photo studio equipment, plotter" value='<?php echo @$row["extras"] ?>' /></td>
        </tr>
        <tr>
            <th>Opening Instructions</th>
            <td><input type="text" name="openinginstructions" placeholder="You'll receive an e-mail with a PIN code that you can use to access the building." value='<?php echo @$row["openinginstructions"] ?>' /></td>
        </tr>
        <tr>
            <th>Address</th>
            <td><input type="text" name="address" placeholder="Jakob-Haringer-Str. 3" value='<?php echo @$row["address"] ?>' /></td>
        </tr>
        <tr>
            <th>E-Mail</th>
            <td><input type="text" name="email" placeholder="johndoe@example.com" value='<?php echo @$row["email"] ?>' /></td>
        </tr>
        <tr>
            <th>Phone</th>
            <td><input type="text" name="phone" placeholder="+43 664 123456789" value='<?php echo @$row["phone"] ?>' /></td>
        </tr>
        <tr>
            <th>Postal Code</th>
            <td><input type="text" name="postal_code" placeholder="5020" value='<?php echo @$row["postal_code"] ?>' /></td>
        </tr>
        <tr>
            <th>City</th>
            <td><input type="text" name="city" placeholder="Salzburg" value='<?php echo @$row["city"] ?>' /></td>
        </tr>
        <tr>
            <th>Lat</th>
            <td><input type="text" name="lat" placeholder="47.734" value='<?php echo @$row["lat"] ?>' /></td>
        </tr>
        <tr>
            <th>Lng</th>
            <td><input type="text" name="lng" placeholder="13.5005" value='<?php echo @$row["lng"] ?>' /></td>
        </tr>
        <tr>
            <th>Price for 2 Hours</th>
            <td><input type="text" name="price_2hours" placeholder="120.50" value='<?php echo @$row["price_2hours"] ?>' /></td>
        </tr>
        <tr>
            <th>Price for 1 Day</th>
            <td><input type="text" name="price_1day" placeholder="120.50" value='<?php echo @$row["price_1day"] ?>' /></td>
        </tr>
        <tr>
            <th>Price for 10 Days</th>
            <td><input type="text" name="price_10days" placeholder="120.50" value='<?php echo @$row["price_10days"] ?>' /></td>
        </tr>
        <tr>
            <th>Price for 1 Month</th>
            <td><input type="text" name="price_1month" placeholder="120.50" value='<?php echo @$row["price_1month"] ?>' /></td>
        </tr>
        <tr>
            <th>Price for 6 Months</th>
            <td><input type="text" name="price_6moths" placeholder="120.50" value='<?php echo @$row["price_6moths"] ?>' /></td>
        </tr>
        <tr>
            <th>Desks</th>
            <td><input type="text" name="desks" placeholder="3" value='<?php echo @$row["desks"] ?>' /></td>
        </tr>
        <tr>
            <th>Open From</th>
            <td><input type="text" name="open_from" placeholder="08:30:00" value='<?php echo $row->open_from == null ? "" : date("H:i:s", strtotime($row["open_from"])) ?>' /></td>
        </tr>
        <tr>
            <th>Open Till</th>
            <td><input type="text" name="open_till" placeholder="18:30:00" value='<?php echo $row->open_till == null ? "" : date("H:i:s", strtotime($row["open_till"])) ?>' /></td>
        </tr>
            <tr>
            <th>Open 24/7 For Fixworkers</th>
            <td><input type="checkbox" name="open_247fixworkers" <?php echo $row["open_247fixworkers"]? "checked='checked'" : "" ?> /></td>
        </tr>
        <tr>
            <th></th>
            <td><a href="<?php echo $this->Url->build(["action" => "changepass", $row->id]); ?>">Change Password</a></td>
        </tr>
        <tr>
            <th></th>
            <td><input type="submit" value="Save" /></td>
        </tr>
    </table>

    
</form>