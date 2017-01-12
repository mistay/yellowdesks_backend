<h2>Pictures</h2>

<table>
    <tr>
        <th>Landlord</th>
        <th>Data</th>
        <th>Edit</th>
        <th>Delete</th>
    </tr>
    <?php foreach ($rows as $row): ?>
    <tr>
        <td><?php echo $row->landlord->name . "<br />" . $row->landlord->address . "<br />" . $row->landlord->postal_code . $row->landlord->city; ?></td>
        <td><pre>todo<?php print_r($row->data); ?></pre></td>
        <td><a href="">Edit</a></td>
        <td><a href="">Delete</a></td>
    </tr>
    <?php endforeach; ?>
</table>