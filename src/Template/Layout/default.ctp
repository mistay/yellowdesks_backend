<?php
$cakeDescription = 'Yellowdesks - new work';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <link rel="icon" type="image/jpeg" href="/yellowdesks/favicon.jpg" />
    
    <?= $this->Html->css('base.css') ?>
    <?= $this->Html->css('cake.css') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
    <div style="background-color: #f9e03e; padding: 20px; font-size: 20px">Yellow Desks :: find workspace near you.</div>
    <div style="float:right; padding-right: 15px"><?= $loggedInAs; ?></div>
    <div style="clear:both"></div>
    <?= $this->Flash->render() ?>
    
    
    <div style="display: flex;">
        <nav style="min-width: 270px">
            <ul>
                <li><a href="<?= $this->Url->build(["controller" => "hosts"]); ?>">Hosts</a></li>
                <li><a href="<?= $this->Url->build(["controller" => "coworkers"]); ?>">Coworkers</a></li>
                <li><a href="<?= $this->Url->build(["controller" => "holidays"]); ?>">Holidays</a></li>
                <li><a href="<?= $this->Url->build(["controller" => "bookings"]); ?>">Bookings</a></li>
                <li><a href="<?= $this->Url->build(["controller" => "payments"]); ?>">Payments</a></li>
                <li><a href="<?= $this->Url->build(["controller" => "pictures"]); ?>">Pictures</a></li>
            </ul>
        </nav>
        <div class="container clearfix">
            <?= $this->fetch('content') ?>
        </div>  
    </div>
    <footer>
    </footer>
</body>
</html>
