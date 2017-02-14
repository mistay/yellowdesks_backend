<h2>Videos</h2>

<table>
    <tr>
        <th><?php echo $this->Paginator->sort('host_id', "Host"); ?></th>
        <th>Data</th>
        <th>Edit</th>
        <th>Delete</th>
    </tr>
    <?php foreach ($rows as $row): ?>
    <tr>
        <td><?php echo $row->host->name . "<br />" . $row->host->address . "<br />" . $row->host->postal_code . $row->host->city; ?></td>
        <?php
            $url = $this->Url->build(["controller" => "videos", "action" => "get", $row->id]);
        ?>
        <td>
            <video width="320" height="240" controls>
              <source src="<?php echo $url; ?>" type="video/mp4">
              Your browser does not support the video tag.
            </video>
        </td>
        <td><a href="">Edit</a></td>
        <td><a href="">Delete</a></td>
    </tr>
    <?php endforeach; ?>
</table>