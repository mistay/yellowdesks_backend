<h2>Hosts</h2>

<?php
// toto: globalize me

setlocale(LC_MONETARY, 'de_DE');

?>
<a href="<?php echo $this->Url->build(["action" => "cru"]); ?>">Add</a>

<table>
    <tr>
        <th>Name</th>
        <th>Sales (calc'ed)</th>
        <th>Title</th>
        <th>Details<br />Extras<br />Opening instructions</th>
        <th>Address</th>
        <th>GPS</th>
        <th>Price</th>
        <th>Open</th>
        <th>Edit</th>
        <th>Delete</th>
    </tr>
    <?php foreach ($rows as $row): ?>
    <tr>
        <td><?php echo $row->name; ?></td>
        <td>
            <?php 
                $total = 0;
                foreach ($row->payments as $payment) { $total += $payment->amount; } 
                echo money_format("%i", $total);
            ?>
        </td>
        <td><?php echo $row->title; ?></td>
        <td><?php echo substr($row->details, 0, 30) . (strlen($row->details) > 30 ? "..." : ""); ?><br />
        <?php echo substr($row->extras, 0, 30) . (strlen($row->extras) > 30 ? "..." : ""); ?><br />
        <?php echo substr($row->openinginstructions, 0, 30) . (strlen($row->openinginstructions) > 30 ? "..." : ""); ?></td>
        <td><?php echo nl2br($row->address); ?><br /><?php echo $row->postal_code; ?><br /><?php echo $row->city; ?></td>
        <td><?php echo $row->lat; ?> <?php echo $row->lng; ?></td>
        <td><?php echo money_format("%i", $row->price_2hours); ?>
        <?php echo money_format("%i", $row->price_1day); ?>
        <?php echo money_format("%i", $row->price_10days); ?>
        <?php echo money_format("%i", $row->price_1month); ?>
        <?php echo money_format("%i", $row->price_6months); ?></td>
        <td><?php echo $row->open_from == null ? "" : date("H:i", strtotime($row->open_from)); ?> - <?php echo $row->open_till == null ? "" : date("H:i", strtotime($row->open_till)); ?></td>
        <td><a href="<?php echo $this->Url->build(["action" => "cru", $row->id]); ?>">Edit</a></td>
        <td><a onclick="return confirm('are you sure?')" href="<?php echo $this->Url->build(["action" => "delete", $row->id]); ?>">Delete</a></td>
    </tr>
    <?php endforeach; ?>
</table>