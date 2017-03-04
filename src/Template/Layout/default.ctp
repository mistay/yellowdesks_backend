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
        .hostname {
            color: grey;
            font-size: 10px;
        }
        .header {
            background-color: #f9e03e; 
            padding: 20px; 
            font-size: 20px;
        }
        .header a {
            color: black;
        }
    </style>
</head>
<body>
    <div class="header"><a href="<?= $this->Url->build("/"); ?>">Yellow Desks :: find workspace near you.</a></div>
    <div style="float:right; padding-right: 15px"><?= $loggedInAs; ?></div>
    <div style="clear:both"></div>
    <?= $this->Flash->render() ?>
    
    
    <div style="display: flex;">
        <nav class="menunav" style="min-width: 170px">
            <?php if($loggedInUser != null) { ?>
            <ul class="menu">
                
                <?php $loggedinuser = $this->request->session()->read('User'); ?>
                
                <?php if ($loggedinuser -> role == "ADMIN") { ?>
                    <a href="<?= $this->Url->build(["controller" => "hosts"]); ?>"><li>Hosts</li></a>
                    <a href="<?= $this->Url->build(["controller" => "coworkers"]); ?>"><li>Coworkers</li></a>
                    <a href="<?= $this->Url->build(["controller" => "holidays"]); ?>"><li>Holidays</li></a>
                    <a href="<?= $this->Url->build(["controller" => "payments"]); ?>"><li>Payments</li></a>
                    <a href="<?= $this->Url->build(["controller" => "paypalipns"]); ?>"><li>PayPal IPNs</li></a>
                    <a href="<?= $this->Url->build(["controller" => "bookings"]); ?>"><li>Bookings</li></a>
                    <a href="<?= $this->Url->build(["controller" => "pictures"]); ?>"><li>Pictures</li></a>
                    <a href="<?= $this->Url->build(["controller" => "logs"]); ?>"><li>Logs</li></a>
                <?php } ?>
                
                <?php if ($loggedinuser -> role == "HOST") { ?>
                    <a href="<?= $this->Url->build(["controller" => "hosts", "action" => "cru"]); ?>"><li>My Details</li></a>
                    <a href="<?= $this->Url->build(["controller" => "pictures", "action" => "index"]); ?>"><li>My Pictures</li></a>
                
                <?php } ?>
                
                
                <?php if ($loggedinuser -> role == "COWORKER") { ?>
                    <a href="<?= $this->Url->build(["controller" => "coworkers", "action" => "cru"]); ?>"><li>My Details</li></a>
                
                <?php } ?>
            </ul>
            
            
            <?php } ?>
            <div class="hostname"><?= gethostname(); ?></div>
            
        </nav>
        <div class="content">
            <?= $this->fetch('content') ?>
        </div>  
    </div>
    <footer>
    </footer>
</body>
</html>
