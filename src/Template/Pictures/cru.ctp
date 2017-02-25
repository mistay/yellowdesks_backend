<h2><?php echo empty($row) ? "Add" : "Edit" ?> Pictures</h2>

<form name="form1" method="post" enctype="multipart/form-data">
    
    <select name="host_id">
        <?php foreach ($rows as $row): ?>
            <option value="<?= $row->id ?>"><?= $row->name . " (id: " . $row->id . ")" ?></option>
        <?php endforeach ?>
    </select>
    <br /><br />
    <?= __("You can choose one or more pictures for upload. Resolution: the more, the better."); ?>
    <input type="file" name="files[]" multiple>
    <br /><br />
    
    <br /><br />
    <input type="submit" value="save" />
    
</form>