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

    <?= $this->Html->css('../fonts/eraser/stylesheet.css') ?>
    <?= $this->Html->css('../fonts/din1451/stylesheet.css') ?>
    <?= $this->Html->css('menu.css') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
    <?= $this->Html->script('yellowdesks.js'); ?>
    <style>
        <?php if ($_SERVER['SERVER_NAME'] == "localhost") { ?>
        .devheader {
            background-color: red;
            font-size: 50px;
            text-align: center;

        }
        <?php } ?>
    </style>
</head>
<body>
    <?php if ($_SERVER['SERVER_NAME'] == "localhost") { ?>
        <div class="devheader" >this is the development system!!</div>
    <?php } ?>
    <div class="burger"><img src="<?= $this->Url->build("/img/burger.png"); ?>" /></div>
    <div class="header"><a href="<?= $this->Url->build("/"); ?>">YELLOW DESKS</a></div>
    <div class="subheader"><a href="<?= $this->Url->build("/"); ?>">find workspace near you</a></div>
    <div class="subsubheader">a <a href="http://coworkingsalzburg.com">coworkingsalzburg.com</a> startup</div>
    <div class="mobilemenu"></div>
    <div style="float:right; padding-right: 15px"><?= $loggedInAs; ?></div>
    <div style="clear:both"></div>
    <?= $this->Flash->render() ?>
    
    
    <div style="display: flex;">
        <div class="menunavdesktopanchor" ></div>
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
                    <a href="<?= $this->Url->build(["controller" => "bookings", "action" => "host"]); ?>"><li>My Bookings</li></a>
                
                <?php } ?>
                
                
                <?php if ($loggedinuser -> role == "COWORKER") { ?>
                    <a href="<?= $this->Url->build(["controller" => "coworkers", "action" => "cru"]); ?>"><li>My Details</li></a>
                    <a href="<?= $this->Url->build(["controller" => "bookings", "action" => "mybookings"]); ?>"><li>My Bookings</li></a>
                    <a href="<?= $this->Url->build(["controller" => "hosts", "action" => "map"]); ?>"><li>Map</li></a>
                
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
