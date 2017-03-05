<h2>My Bookings</h2>

<table>
    <tr>
        <th>Booking Date</th>
        <th>Host</th>
        <th>Descripton</th>
        <th>Begin Date</th>
        <th>End Date</th>
        <th>Price</th>
        <th>Invoice</th>
        
    </tr>
    <?php foreach ($rows as $row): ?>
    <tr>
        <td><?php echo date("d.m.Y H:i", strtotime($row->dt_inserted)); ?></td>
        <td><?php echo $row->host->name . " (" . $row->host->nickname . ")"; ?></td>
        <td><?php echo $row->description; ?></td>
        <td><?php echo date("d.m.Y H:i", strtotime($row->begin)); ?></td>
        <td><?php echo date("d.m.Y H:i", strtotime($row->end)); ?></td>
        <?php
            $url = $this->Url->build(["controller" => "bookings", "action" => "invoice", $row->id]);
            $urlhost = $this->Url->build(["controller" => "bookings", "action" => "invoicehost", $row->host->id]);
        ?>
        <td><?php echo $row->price . "EUR (VAT: " . $row->vat . ")"; ?></td>
        <td><a href="<?= $url ?>">Invoice</a></td>
        
    </tr>
    <?php endforeach; ?>
</table>