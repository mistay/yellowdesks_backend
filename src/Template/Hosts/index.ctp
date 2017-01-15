<h2>Hosts</h2>

<?php
// toto: globalize me

setlocale(LC_MONETARY, 'de_DE');

?>


<table>
    <tr>
        <th>Name</th>
        <th>Total Payments</th>
        <th>Image</th>
        <th>address</th>
        <th>postal_code</th>
        <th>city</th>
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
        <?php
            $url = $this->Url->build(["controller" => "pictures", "action" => "get", $row->picture_id]);
            $url100cropped = $this->Url->build(["controller" => "pictures", "action" => "get", $row->picture_id, "resolution" => "100x100", "crop" => "true"]);
        ?>
        <td><?php if ($row->picture_id > 0) { ?><a href="<?php echo $url ?>"><img alt="" src='<?php echo $url100cropped ?>'></img></a><?php }?></td>
        <td><?php echo nl2br($row->address); ?></td>
        <td><?php echo $row->postal_code; ?></td>
        <td><?php echo $row->city; ?></td>
        <td><a href="">Edit</a></td>
        <td><a href="">Delete</a></td>
    </tr>
    <?php endforeach; ?>
</table>