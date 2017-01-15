<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Datasource\ConnectionManager;
use Cake\Error\Debugger;
use Cake\Network\Exception\NotFoundException;

$this->layout = false;

if (!Configure::read('debug')):
    throw new NotFoundException('Please replace src/Template/Pages/home.ctp with your own version.');
endif;

$cakeDescription = 'CakePHP: the rapid development PHP framework';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>yellowdesks</title>
        <meta name="description" content="">
        <link rel="stylesheet" href="css/main.css">
        <link rel="stylesheet" href="css/jquery.steps.css">
        <link rel="stylesheet" href="fonts/eraser/stylesheet.css">
        <link rel="stylesheet" href="fonts/din1451/stylesheet.css">
        
        <?php
            $url = $this->Url->build('/favicon.jpg', true);
        ?>
        
        <link rel="icon" type="image/jpeg" href="<?= $url ?>" />
    
        <script src="js/jquery-1.9.1.min.js"></script>
        
        <style>
            body {
                background: url("img/eva.jpg") no-repeat center center fixed;
                background-size: cover;
                padding: 0px;
                margin: 0px;
                bottom: 0px;
                font-family: din;
                
            }
            
            .footer {
                position: absolute;
                bottom: 10px;
                width: 100%;
                text-align: center;
                color: white;
            }
            .footer a {
                color: white;
                text-decoration: none;
            }
            
            .coworkingsalzburg {
            }
            
            .yellowdesks {
                background-color: #f3ed3d;
                font-size: 55px;
                margin-left: 50px;
                font-family: eraserregular;
            
            }
            
            .findandrent {
                font-size: 20px;
                background-color: #f3ed3d;
                margin-left: 50px;
            }
            
            .content {
                padding-top: 25%;
            }
            
            .menu {
                padding-top: 10px;
                padding-right: 10px;
                display: flex;
                flex-direction: row;
                justify-content: flex-end;
            }
            
            .menu a {
                background-color: white;
                min-width: 140px;
                display: inline-block;
                margin: 5px;
                padding: 5px;
                padding-left: 20px;
                text-decoration: none;
                color: black;
            }
        </style>
        
        <script>
            $("#finish").onclick = function() {
                alert("f90");
            }
        </script>
    </head>
    <body>
        <div class="menu">
            <a href="">Jetzt Host werden</a>
            <?php
            $url = $this->Url->build([
                "controller" => "users",
                "action" => "login",
            ]);
            ?>
            
            <a href="<?= $url ?>">Login</a>
            <a href="">Registrieren</a>
        </div>
        
        
        <div class="content">
            <span class="yellowdesks">yellow desks</span>
        </div>
        <div>
            <span class="findandrent">&gt; &gt; <strong>Find</strong> work space near you</span>
        </div>
        <div>
            <span class="findandrent">&gt; &gt; <strong>Rent out</strong> work space</span>
        </div>
        
        <div class="footer"><a href="http://coworkingsalzburg.com">by <span class="coworkingsalzburg"><strong>COWORKING</strong>SALZBURG</span></a></div>
    </body>
</html>