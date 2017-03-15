<h2>Bookings</h2>

<table>
    <tr>
        <th>Paid</th>
        <th>Booking Date (GMT)</th>
        <th>Duration</th>
        <th>Price + VAT</th>
        <th>Service Fee</th>
        <th>Host + VAT</th>
        <th>Host</th>
        <th>Coworker</th>
    </tr>
    <?php foreach ($rows as $row): ?>
    <tr>
        <td><?= $row -> paypalipn_id == null ? "no" : "yes" ?></td>
        <td><?php echo date("d.m.Y H:i", strtotime($row->dt_inserted)); ?> (id: <?= $row -> id ?>)</td>
        <td><?php echo date("d.m.Y", strtotime($row->begin)); ?>
            <?php echo date("d.m.Y", strtotime($row->end)); ?></td>
        <?php
            $url = $this->Url->build(["controller" => "bookings", "action" => "invoice", $row->id]);
        ?>
        <td><?= money_format('%i', $row->price) ?> + <?= money_format('%i', $row->vat) ?></td>
        <td><?= money_format('%i', $row->servicefee_host) ?></td>
        <td><?= money_format('%i', $row->amount_host) ?> + <?= money_format('%i', $row->vat_host) ?></td>
        <td><?php echo $row->host->name . " (" . $row->host->nickname . ")"; ?></td>
        <td><?php echo $row->coworker->companyname . "<br />" . $row->coworker->firstname . $row->coworker->lastname; ?><br /><a href="<?= $url; ?>">Invoice</a></td>
    </tr>
    <?php endforeach; ?>
</table>