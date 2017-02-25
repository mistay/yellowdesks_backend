<h2>Pictures</h2>

<a href="<?php echo $this->Url->build(["action" => "cru"]); ?>">Add</a>
<br /><br />
<?php foreach ($rows as $row): ?>
    <?php
        $url = $this->Url->build(["controller" => "pictures", "action" => "get", $row->id]);
        $url100 = $this->Url->build(["controller" => "pictures", "action" => "get", $row->id, "resolution" => "100x100"]);
        $url100cropped = $this->Url->build(["controller" => "pictures", "action" => "get", $row->id, "resolution" => "100x100", "crop" => "true"]);
    ?>
    <a href="<?php echo $url ?>"><img alt="" src='<?php echo $url100cropped ?>' /></a>
<?php endforeach; ?>
