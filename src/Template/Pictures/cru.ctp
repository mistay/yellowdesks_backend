<h2>Add Picture</h2>

<form name="form1" method="post" enctype="multipart/form-data">
    <?php $loggedinuser = $this->request->session()->read('User'); ?>
         
    <?php if ($loggedinuser -> role == "ADMIN") { ?>
        <select name="host_id">
            <?php foreach ($hosts as $row): ?>
                <option value="<?= $row->id ?>"><?= $row->name . " (id: " . $row->id . ")" ?></option>
            <?php endforeach ?>
        </select>
    <?php } ?>

    <br /><br />
    <?= __("You can choose one or more pictures for upload. Resolution: the more, the better."); ?>
    <input type="file" name="files[]" multiple>
    <br /><br />
    
    <br /><br />
    <input type="submit" value="save" />
    
</form>