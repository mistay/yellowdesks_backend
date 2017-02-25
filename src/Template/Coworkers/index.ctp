<h2>Coworkers</h2>

<table>
    <tr>
        <th>Firstname</th>
        <th>Lastname</th>
        <th>Username</th>
        <th>Companyname<br />VAT ID</th>
        <th>E-Mail</th>
        <th>Edit</th>
        <th>Delete</th>
    </tr>
    <?php foreach ($rows as $row): ?>
    <tr>
        <td><?php echo $row->firstname ?></td>
        <td><?php echo $row->lastname; ?></td>
        <td><?php echo $row->username; ?></td>
        <td><?php echo $row->companyname; ?><br /><?php echo $row->vatid; ?></td>
        <td><a href="mailto:<?= $row->email; ?>"><?= $row->email; ?></a></td>
        
        <td><a href="">Edit</a></td>
        <td><a href="">Delete</a></td>
    </tr>
    <?php endforeach; ?>
</table>