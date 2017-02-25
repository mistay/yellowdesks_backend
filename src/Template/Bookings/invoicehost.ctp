<h2>Booking Overview</h2>

<?php echo $host->name . " (Nickname: " . $host->nickname . ")"; ?>
<br />
<?php echo $host->address; ?>
<br />
<?php echo $host->postal_code . " " . $host->city; ?>
<br />
<?php echo $host->email; ?>
<br />


<?php
$total=0;
?>


<table>
    <tr>
        <th>Date</th>
        <th>Coworker</th>
        <th>Price</th>
        <th>VAT</th>
        <th>Service Fee</th>
        <th>Total</th>
    </tr>
    <?php foreach ($rows as $row): ?>
    <tr>
        <td><?php echo date("d.m.Y", strtotime($row->dt_inserted)); ?></td>
        <td><?php echo $row->coworker->companyname . " " . $row->coworker->firstname . " " . $row->coworker->lastname; ?></td>
        <td><?php echo money_format('%i', $row->price); ?></td>
        <td><?php echo $row->vat; ?>%</td>
        <td><?php echo money_format('%i', $row->servicefee_host); ?></td>
        <?php $subtotal = $row->price * (1+$row->vat/100) + $row->servicefee_host; $total += $subtotal // todo: sum??? sum financially, not mathematically ?>
        <td><?php echo money_format('%i', $subtotal); ?></td>
    </tr>
    <?php endforeach; ?>
    <tr>
        <td colspan="5">
            
        </td>
        <td><h2>
            <?php echo money_format('%i', $total); ?>
            </h2>
        </td>
    </tr>
</table>
