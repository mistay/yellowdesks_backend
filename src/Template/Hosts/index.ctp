<h2>Landlords</h2>

<table>
    <tr>
        <th>Name</th>
        <th>address</th>
        <th>postal_code</th>
        <th>city</th>
        <th>pictures</th>
        <th>Edit</th>
        <th>Delete</th>
    </tr>
    <?php foreach ($rows as $row): ?>
    <tr>
        <td><?php echo $row->name; ?></td>
        <td><?php echo $row->address; ?></td>
        <td><?php echo $row->postal_code; ?></td>
        <td><?php echo $row->city; ?></td>
        <td><pre>todo<?php print_r($row); ?></pre></td>
        <td><a href="">Edit</a></td>
        <td><a href="">Delete</a></td>
    </tr>
    <?php endforeach; ?>
</table>