<div class="users form">
<?= $this->Flash->render('errors') ?>
    
<?php 
    $someone = $this->request->session()->read('User');
    if (isset($someone->username)) {
        echo __('You are logged in as {0} with username {1}', $someone->role, $someone->username);
        
        ?>
        <a href="<?= $this->Url->build(["controller" => "users", "action" => "logout"]); ?>"><?= __('Logout'); ?></a>
        <?php
        
    } else {
    ?>
<?= $this->Form->create() ?>
        <?= __('Please enter your username and password') ?>
        <br /><br />
        <?= $this->Form->input('username') ?>
        <?= $this->Form->input('password') ?>
<?= $this->Form->button(__('Login')); ?>
<?= $this->Form->end() ?>
    
    <?php } ?>
</div>
