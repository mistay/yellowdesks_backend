<h2><?php echo empty($row) ? "Add" : "Edit" ?> Host</h2>

<form name="form1" method="post">
    <table>
        <tr>
            <th>Name</th>
            <td><input type="text" name="name" value='<?php echo @$row["name"] ?>' /></td>
        </tr>
        <tr>
            <th>Name</th>
            <td><input type="text" name="nickname" value='<?php echo @$row["nickname"] ?>' /></td>
        </tr>
        <tr>
            <th>Title</th>
            <td><input type="text" name="title" value='<?php echo @$row["title"] ?>' /></td>
        </tr>
        <tr>
            <th>Details</th>
            <td><input type="text" name="details" value='<?php echo @$row["details"] ?>' /></td>
        </tr>
        <tr>
            <th>Extras</th>
            <td><input type="text" name="extras" value='<?php echo @$row["extras"] ?>' /></td>
        </tr>
        <tr>
            <th>Openinginstructions</th>
            <td><input type="text" name="openinginstructions" value='<?php echo @$row["openinginstructions"] ?>' /></td>
        </tr>
        <tr>
            <th>address</th>
            <td><input type="text" name="address" value='<?php echo @$row["address"] ?>' /></td>
        </tr>
        <tr>
            <th>postal_code</th>
            <td><input type="text" name="postal_code" value='<?php echo @$row["postal_code"] ?>' /></td>
        </tr>
        <tr>
            <th>city</th>
            <td><input type="text" name="city" value='<?php echo @$row["city"] ?>' /></td>
        </tr>
        <tr>
            <th>lat</th>
            <td><input type="text" name="lat" value='<?php echo @$row["lat"] ?>' /></td>
        </tr>
        <tr>
            <th>lng</th>
            <td><input type="text" name="lng" value='<?php echo @$row["lng"] ?>' /></td>
        </tr>
        <tr>
            <th>price 2 hours</th>
            <td><input type="text" name="price_2hours" value='<?php echo @$row["price_2hours"] ?>' /></td>
        </tr>
        <tr>
            <th>price 1 day</th>
            <td><input type="text" name="price_1day" value='<?php echo @$row["price_1day"] ?>' /></td>
        </tr>
        <tr>
            <th>price 10 days</th>
            <td><input type="text" name="price_10days" value='<?php echo @$row["price_10days"] ?>' /></td>
        </tr>
        <tr>
            <th>price 1 month</th>
            <td><input type="text" name="price_1month" value='<?php echo @$row["price_1month"] ?>' /></td>
        </tr>
        <tr>
            <th>price 6 moths</th>
            <td><input type="text" name="price_6moths" value='<?php echo @$row["price_6moths"] ?>' /></td>
        </tr>
        <tr>
            <th>open_from</th>
            <td><input type="text" name="open_from" value='<?php echo $row->open_from == null ? "" : date("H:i:s", strtotime($row["open_from"])) ?>' /></td>
        </tr>
        <tr>
            <th>open_till</th>
            <td><input type="text" name="open_till" value='<?php echo $row->open_till == null ? "" : date("H:i:s", strtotime($row["open_till"])) ?>' /></td>
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