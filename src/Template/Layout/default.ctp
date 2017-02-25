<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= "Yellowdesks" ?> :: <?= $this->fetch('title') ?>
    </title>
    <link rel="icon" type="image/jpeg" href="/yellowdesks/favicon.jpg" />
    
    <?= $this->Html->css('yellowdesks.css') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
    
    <style>
        .menu {
            padding: 10px;
            margin: 0px;
            
        }
        
        .menu li {
            list-style: none;
            padding: 5px;
        }
        .menunav li:hover {
            background: #f9e03e;
            
        }
        .menu > a {
            color: black;
        }
        
        .menu a:hover {
            text-decoration: none;
        }
        .menunav {
            border-right: 1px solid lightgrey;
        }
        
        .content {
            padding-left: 20px;
            width: 100%;
        }
    </style>
</head>
<body>
    <div style="background-color: #f9e03e; padding: 20px; font-size: 20px">Yellow Desks :: find workspace near you.</div>
    <div style="float:right; padding-right: 15px"><?= $loggedInAs; ?></div>
    <div style="clear:both"></div>
    <?= $this->Flash->render() ?>
    
    
    <div style="display: flex;">
        <nav class="menunav" style="min-width: 170px">
            <?php if($loggedInUser != null) { ?>
            <ul class="menu">
                <a href="<?= $this->Url->build(["controller" => "hosts"]); ?>"><li>Hosts</li></a>
                <a href="<?= $this->Url->build(["controller" => "coworkers"]); ?>"><li>Coworkers</li></a>
                <a href="<?= $this->Url->build(["controller" => "holidays"]); ?>"><li>Holidays</li></a>
                <a href="<?= $this->Url->build(["controller" => "bookings"]); ?>"><li>Bookings</li></a>
                <a href="<?= $this->Url->build(["controller" => "payments"]); ?>"><li>Payments</li></a>
                <a href="<?= $this->Url->build(["controller" => "pictures"]); ?>"><li>Pictures</li></a>
                <a href="<?= $this->Url->build(["controller" => "logs"]); ?>"><li>Logs</li></a>
            </ul>
            <?php } ?>
            
        </nav>
        <div class="content">
            <?= $this->fetch('content') ?>
        </div>  
    </div>
    <footer>
    </footer>
</body>
</html>
