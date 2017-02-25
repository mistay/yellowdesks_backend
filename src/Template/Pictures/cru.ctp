<h2><?php echo empty($row) ? "Add" : "Edit" ?> Picture</h2>

<form name="form1" method="post" enctype="multipart/form-data">
    
    <select name="host_id">
        <?php foreach ($rows as $row): ?>
            <option value="<?= $row->id ?>"><?= $row->name . " (id: " . $row->id . ")" ?></option>
        <?php endforeach ?>
    </select>
    
    <input type="file" name="files[]" multiple>
    <input type="submit" value="save" />
    
</form>