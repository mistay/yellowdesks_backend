<h2><?php echo empty($row) ? "Add" : "Edit" ?> Host</h2>

<form name="form1" method="post">
    <table>
        <tr>
            <th>Name</th>
            <td><input type="text" name="name" placeholder="My Company Ltd" value='<?php echo @$row["name"] ?>' /></td>
        </tr>
        <tr>
            <th>Name</th>
            <td><input type="text" name="nickname" placeholder="John" value='<?php echo @$row["nickname"] ?>' /></td>
        </tr>
        <tr>
            <th>Title</th>
            <td><input type="text" name="title" placeholder="Creative agency downtown Salzburg" value='<?php echo @$row["title"] ?>' /></td>
        </tr>
        <tr>
            <th>Details</th>
            <td><input type="text" name="details" placeholder="high speed wifi, business printer both a4 and a3, coffee" value='<?php echo @$row["details"] ?>' /></td>
        </tr>
        <tr>
            <th>Extras</th>
            <td><input type="text" name="extras" placeholder="parking lot, photo studio equipment, plotter" value='<?php echo @$row["extras"] ?>' /></td>
        </tr>
        <tr>
            <th>Openinginstructions</th>
            <td><input type="text" name="openinginstructions" placeholder="You are welcome - please have a look at the business hours." value='<?php echo @$row["openinginstructions"] ?>' /></td>
        </tr>
        <tr>
            <th>address</th>
            <td><input type="text" name="address" placeholder="Jakob-Haringer-Str. 3" value='<?php echo @$row["address"] ?>' /></td>
        </tr>
        <tr>
            <th>postal_code</th>
            <td><input type="text" name="postal_code" placeholder="5020" value='<?php echo @$row["postal_code"] ?>' /></td>
        </tr>
        <tr>
            <th>city</th>
            <td><input type="text" name="city" placeholder="Salzburg" value='<?php echo @$row["city"] ?>' /></td>
        </tr>
        <tr>
            <th>lat</th>
            <td><input type="text" name="lat" placeholder="47.734" value='<?php echo @$row["lat"] ?>' /></td>
        </tr>
        <tr>
            <th>lng</th>
            <td><input type="text" name="lng" placeholder="13.5005" value='<?php echo @$row["lng"] ?>' /></td>
        </tr>
        <tr>
            <th>price 2 hours</th>
            <td><input type="text" name="price_2hours" placeholder="120.50" value='<?php echo @$row["price_2hours"] ?>' /></td>
        </tr>
        <tr>
            <th>price 1 day</th>
            <td><input type="text" name="price_1day" placeholder="120.50" value='<?php echo @$row["price_1day"] ?>' /></td>
        </tr>
        <tr>
            <th>price 10 days</th>
            <td><input type="text" name="price_10days" placeholder="120.50" value='<?php echo @$row["price_10days"] ?>' /></td>
        </tr>
        <tr>
            <th>price 1 month</th>
            <td><input type="text" name="price_1month" placeholder="120.50" value='<?php echo @$row["price_1month"] ?>' /></td>
        </tr>
        <tr>
            <th>price 6 moths</th>
            <td><input type="text" name="price_6moths" placeholder="120.50" value='<?php echo @$row["price_6moths"] ?>' /></td>
        </tr>
        <tr>
            <th>open_from</th>
            <td><input type="text" name="open_from" placeholder="08:30:00" value='<?php echo $row->open_from == null ? "" : date("H:i:s", strtotime($row["open_from"])) ?>' /></td>
        </tr>
        <tr>
            <th>open_till</th>
            <td><input type="text" name="open_till" placeholder="18:30:00" value='<?php echo $row->open_till == null ? "" : date("H:i:s", strtotime($row["open_till"])) ?>' /></td>
        </tr>
            <tr>
            <th>open_247fixworkers</th>
            <td><input type="checkbox" name="open_247fixworkers" <?php echo $row["open_247fixworkers"]? "checked='checked'" : "" ?> /></td>
        </tr>
        <tr>
            <th></th>
            <td><input type="submit" value="save" /></td>
        </tr>
    </table>

    
</form>