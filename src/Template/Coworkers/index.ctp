<h2>Coworkers</h2>

<table>
    <tr>
        <th>Firstname</th>
        <th>Lastname</th>
        <th>Username</th>
        <th>Companyname</th>
        <th>VAT ID</th>
        <th>E-Mail</th>
        <th>Edit</th>
        <th>Delete</th>
    </tr>
    <?php foreach ($rows as $row): ?>
    <tr>
        <td><?php echo $row->firnstame ?></td>
        <td><?php echo $row->lastname; ?></td>
        <td><?php echo $row->username; ?></td>
        <td><?php echo $row->companyname; ?></td>
        <td><?php echo $row->vatid; ?></td>
        <td><?php echo $row->email; ?></td>
        
        <td><a href="">Edit</a></td>
        <td><a href="">Delete</a></td>
    </tr>
    <?php endforeach; ?>
</table>