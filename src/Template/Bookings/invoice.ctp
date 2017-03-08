<?php echo $row->coworker->companyname; ?>
<br />
<?php echo $row->coworker->firstname . " " . $row->coworker->lastname; ?>
<br />
<?php echo $row->coworker->address; ?><br />
<?php echo $row->coworker->email; ?>

<h2>Invoice</h2>

<table>
    <tr>
        <th>Date</th>
        <td><?php echo date("d.m.Y", strtotime($row->dt_inserted)); ?></td>
    </tr>
    <tr>
        <th>Host</th>
        <td><?php echo $row->host->name; ?></td>
    </tr>
</table>

<table>
    <tr>
        <th>Quantity</th>
        <th>Description</th>
        <th>Unit Cost</th>
        <th>VAT</th>
        <th>Service Fee</th>
        <th>Total</th>
    </tr>
    <tr>
        <td>1</td>
        <td><?php echo $row->description; ?></td>
        <td><?php echo money_format('%i', $row->price); ?></td>
        <td><?php echo money_format('%i', $row->vat); ?></td>
        <td><?php echo money_format('%i', $row->servicefee_coworker) ?></td>
        <?php $total = $row->price * (1 + ($row->vat/100)) + $row->servicefee_coworker; ?>
        <td><?php echo money_format('%i', $total); ?></td>
        
        
        
        
    </tr>
</table>
