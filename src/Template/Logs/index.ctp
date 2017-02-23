<h2>Last 100 Log Entries</h2>

<table>
        <th>logged</th>
        <th>message</th>
    <tr>
    </tr>
<?php foreach($rows as $row) { ?>
    <tr>
        <td><?= $row["ts_logged"]; ?></td>
        <td><?= $row["message"]; ?></td>
    </tr>
    
<?php } ?>
    
</table>