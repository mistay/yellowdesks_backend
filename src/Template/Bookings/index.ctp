<h2>Bookings</h2>

<table>
    <tr>
        <th>Date Time</th>
        <th>Invoice Host</th>
        <th>Invoice Coworker</th>
        <th>Price</th>
        <th>Service Fee Host</th>
        <th>Service Fee Coworker</th>
        <th>Descripton</th>
        <th>Host</th>
        <th>Coworker</th>
    </tr>
    <?php foreach ($rows as $row): ?>
    <tr>
        <td><?php echo date("d.m.Y H:i", strtotime($row->dt_inserted)); ?></td>
        
        <?php
            $url = $this->Url->build(["controller" => "bookings", "action" => "invoice", $row->id]);
            $urlhost = $this->Url->build(["controller" => "bookings", "action" => "invoicehost", $row->host->id]);
        ?>
        
        <td><a href="<?php echo $urlhost; ?>">Invoice</a></td>
        <td><a href="<?php echo $url; ?>">Invoice</a></td>
        <td><?php echo $row->price . "EUR (" . $row->vat . "%)"; ?></td>
        <td><?php echo $row->servicefee_host . "EUR" ?></td>
        <td><?php echo $row->servicefee_coworker . "EUR" ?></td>
        <td><?php echo $row->description; ?></td>
        <td><?php echo $row->host->name . " (" . $row->host->nickname . ")"; ?></td>
        <td><?php echo $row->coworker->companyname . "<br />" . $row->coworker->firstname . $row->coworker->lastname; ?></td>
    </tr>
    <?php endforeach; ?>
</table>