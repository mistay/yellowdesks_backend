<h2>Pictures</h2>

<table>
    <tr>
        <th>Host</th>
        <th>Data</th>
        <th>Edit</th>
        <th>Delete</th>
    </tr>
    <?php foreach ($rows as $row): ?>
    <tr>
        <td><?php echo $row->host->name . "<br />" . $row->host->address . "<br />" . $row->host->postal_code . $row->host->city; ?></td>
        <td>
            <?php
                $url = $this->Url->build(["controller" => "pictures", "action" => "get", $row->id]);
            ?>
            <a href="<?php echo $url ?>"><img alt="" src='<?php echo $url ?>'></img></a></td>
        <td><a href="">Edit</a></td>
        <td><a href="">Delete</a></td>
    </tr>
    <?php endforeach; ?>
</table>