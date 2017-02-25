<h2>Pictures</h2>

<a href="<?php echo $this->Url->build(["action" => "cru"]); ?>">Add</a>

<table>
    <tr>
        <th><?php echo $this->Paginator->sort('host_id', "Host"); ?></th>
        <th>Data 100x100</th>
        <th>Data 100x100 cropped</th>
        <th>Edit</th>
        <th>Delete</th>
    </tr>
    <?php foreach ($rows as $row): ?>
    <tr>
        <td><?php echo $row->host->name . "<br />" . $row->host->address . "<br />" . $row->host->postal_code . $row->host->city; ?></td>
        <?php
            $url = $this->Url->build(["controller" => "pictures", "action" => "get", $row->id]);
            $url100 = $this->Url->build(["controller" => "pictures", "action" => "get", $row->id, "resolution" => "100x100"]);
            $url100cropped = $this->Url->build(["controller" => "pictures", "action" => "get", $row->id, "resolution" => "100x100", "crop" => "true"]);
        ?>
        <td><a href="<?php echo $url ?>"><img alt="" src='<?php echo $url100 ?>'></img></a></td>
        <td><a href="<?php echo $url ?>"><img alt="" src='<?php echo $url100cropped ?>'></img></a></td>
        <td><a href="">Edit</a></td>
        <td><a href="">Delete</a></td>
    </tr>
    <?php endforeach; ?>
</table>