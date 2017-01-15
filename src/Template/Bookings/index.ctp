<h2>Bookings</h2>

<table>
    <tr>
        <th>Date Time</th>
        <th>Price</th>
        <th>Service Fee</th>
        <th>Descripton</th>
        <th>Host</th>
        <th>Coworker</th>
    </tr>
    <?php foreach ($rows as $row): ?>
    <tr>
        <td><?php echo date("d.m.Y H:i", strtotime($row->dt_inserted)); ?></td>
        <td><?php echo $row->price . "EUR (" . $row->vat . "%)"; ?></td>
        <td><?php echo $row->servicefee . "EUR" ?></td>
        <td><?php echo $row->description; ?></td>
        <td><?php echo $row->host->name . " (" . $row->host->nickname . ")"; ?></td>
        <td><?php echo $row->coworker->companyname . "<br />" . $row->coworker->firstname . $row->coworker->lastname; ?></td>
    </tr>
    <?php endforeach; ?>
</table>