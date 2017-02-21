<h2>Hosts</h2>

<?php
// toto: globalize me

setlocale(LC_MONETARY, 'de_DE');

?>
<a href="<?php echo $this->Url->build(["action" => "cru"]); ?>">Add</a>

<table>
    <tr>
        <th>Name</th>
        <th>Title</th>
        <th>Details</th>
        <th>Total Payments (calc'ed)</th>
        <th>Image</th>
        <th>address</th>
        <th>postal_code</th>
        <th>city</th>
        <th>lat</th>
        <th>lng</th>
        <th>lat_loose</th>
        <th>lng_loose</th>
        <th>price 2 hours</th>
        <th>price 1 day</th>
        <th>price 10 days</th>
        <th>price 1 month</th>
        <th>price 6 moths</th>
        <th>Edit</th>
        <th>Delete</th>
    </tr>
    <?php foreach ($rows as $row): ?>
    <tr>
        <td><?php echo $row->name; ?></td>
        <td><?php echo $row->title; ?></td>
        <td><?php echo $row->details; ?></td>
        <td>
            <?php 
                $total = 0;
                foreach ($row->payments as $payment) { $total += $payment->amount; } 
                echo money_format("%i", $total);
            ?>
        </td>
        <?php
            
            $url = $this->Url->build(["controller" => "pictures", "action" => "get", $row->picture_id]);
            $url100cropped = $this->Url->build(["controller" => "pictures", "action" => "get", $row->picture_id, "resolution" => "100x100", "crop" => "true"]);
        ?>
        <td>
            <?php if ($row->picture_id > 0) { ?>
                <a href="<?php echo $url; ?>">
                <img alt="" src="<?php echo $url100cropped; ?>" />
            </a>
            <?php } ?>
        </td>
        
        <td><?php echo nl2br($row->address); ?></td>
        <td><?php echo $row->postal_code; ?></td>
        <td><?php echo $row->city; ?></td>
        <td><?php echo $row->lat; ?></td>
        <td><?php echo $row->lng; ?></td>
        <td><?php echo $row->lat_loose; ?></td>
        <td><?php echo $row->lng_loose; ?></td>
        <td><?php echo money_format("%i", $row->price_2hours); ?></td>
        <td><?php echo money_format("%i", $row->price_1day); ?></td>
        <td><?php echo money_format("%i", $row->price_10days); ?></td>
        <td><?php echo money_format("%i", $row->price_1month); ?></td>
        <td><?php echo money_format("%i", $row->price_6months); ?></td>
        <td><a href="<?php echo $this->Url->build(["action" => "cru", $row->id]); ?>">Edit</a></td>
        <td><a href="<?php echo $this->Url->build(["action" => "delete", $row->id]); ?>">Delete</a></td>
    </tr>
    <tr>
        <td></td>
        <td colspan="14">
            <?php
            foreach ($row->pictures as $picture) { 
                $link = $this->Url->build(["controller" => "pictures", "action" => "get", $picture->id, "resolution" => "150x150", "crop" => "true"]);
                $url = $this->Url->build(["controller" => "pictures", "action" => "get", $picture->id, "resolution" => "150x150", "crop" => "true"]);
                ?>
                <a href="<?php echo $link; ?>">
                <img alt="" src="<?php echo $url; ?>" />
                </a>
            <?php } ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>