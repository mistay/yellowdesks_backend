<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Mailer\Email;


class UsersController extends AppController
{
    public function signup() {
    }

    public function forgotpassword() {
        $modelCoworkers = TableRegistry::get('Coworkers');
        $modelHosts = TableRegistry::get('Hosts');

        if ($this -> request -> is('post')) {
            $email = $this -> request -> data['email'];

            if (strlen($email) < 3) {
                $this -> Flash -> success (__("Please provide an E-Mail address with at least 3 characters."));
                return;
            }

            $query = $modelCoworkers -> find('all') -> where(["Coworkers.email LIKE" => '%' . $email . '%']);
            $coworkers = $query->toArray();

            $query = $modelHosts -> find('all') -> where(["Hosts.email LIKE" => '%' . $email . '%']);
            $hosts = $query->toArray();
            
            $num = (sizeof($coworkers) + sizeof($hosts));
            if ($num < 1) {
                $this -> Flash -> success (__("Sorry, no such E-Mail address found."));
                return;
            } elseif ($num > 1) {
                $this -> Flash -> success (__("Sorry, more than one E-Mail address found. Please provide a unique E-Mail address."));
                return;
            } else {
                // genau eine e-mail adresse gefunden

                if (sizeof($coworkers) == 1)
                    foreach ($coworkers as $row) {
                        //echo "mail:" . $row -> email;
                    }
                if (sizeof($hosts) == 1)
                    foreach ($hosts as $row) {
                        //echo "mail:" . $row -> email;
                    }

                $to = $row["email"];

                // Sample SMTP configuration.
                Email::setConfigTransport('langhofer', [
                    'host' => 'ssl://ssl.langhofer.at',
                    'port' => 25,
                    'username' => 'armin',
                    'password' => 'phOOb4r*',
                    'className' => 'Smtp',
                    'tls' => true
                ]);

                $email = new Email();
                $email->setTransport('langhofer');
                $ret = $email
                    ->setTemplate('default')
                    ->setLayout('default')
                    ->setEmailFormat('both')
                    ->setTo('office@langhofer.at')
                    ->setFrom('office@langhofer.at')
                    ->send();

                echo "foo"; var_dump($ret);

            }
        }
    }
    
    public function loginappfb() {
        $ret = [];
        $ret["success"] = $this -> hasAccess([Roles::COWORKER]);
        if ( $ret["success"] ) {

            // todo: remove password field from result!!
            $loggedinuser = $this->getloggedInUser();
            $ret["loggedinuser"] = $loggedinuser;
        }
        $this->autoRender = false;
        $this->response->type('application/json');
        $this->response->body(json_encode($rets, JSON_PRETTY_PRINT));
    }
    
    function logout() {
        $this -> logoutSession();
        $this -> redirect('/');
    }
    
    public function home() {
        $model = TableRegistry::get('Hosts');
        $query = $model->find('all');

        $this->set("rows", $query);

        $loggedinuser = $this -> getLoggedInUser();
        $this->set("loggedinuser", $loggedinuser);
    }

    public function welcome() {
        $loggedinuser = $this -> getLoggedInUser();
        if ($loggedinuser -> role == Roles::ADMIN) {
            $this -> redirect(["controller" => "bookings", "action" => "index"]);
        } else if ($loggedinuser -> role == Roles::HOST) {
            $this -> redirect(["controller" => "bookings", "action" => "host"]);
        } else if ($loggedinuser -> role == Roles::COWORKER) {
            $this -> redirect(["controller" => "bookings", "action" => "mybookings"]);
        }
    }

    function getdetails() {
        $this->basicauth();
        $ret=[];
        $ret["error"] = "unknown error";
        
        $loggedinuser = $this -> getLoggedInUser();
        
        if ($loggedinuser == null) {
            $ret["error"] = "invalid username or password";
        } else {
            $ret["error"] = "";
            $ret["username"] = $loggedinuser->username;
            $ret["firstname"] = $loggedinuser->firstname;
            $ret["lastname"] = $loggedinuser->lastname;
        }
        
        $this->autoRender = false;
        $this->response->type('application/json');
        $this->response->body(json_encode($rets, JSON_PRETTY_PRINT));
    }
    
    function login() {
        $this->basicauth();
        if ($this -> getLoggedInUser() != null) {
            // user logged in
            
            //$target = $this -> initMenu(true);
            //$this -> redirect($target);
        }
        $success = null;
        
        if ($this->request->is('post')) {
            $username = $this -> request -> data['username'];
            $password = $this -> request -> data['password'];

            $this -> auth($username, $password);
            
            $this->redirect(["action" => "welcome"]);
        }
        
        if ($this -> getLoggedInUser() != null) {
            if (isset($_REQUEST["redirect_url"])) {
                //echo "redirecting..." . $_REQUEST["redirect_url"] ... $this->redirect() does not redirect to absolute 
                // urls and thus /yellowdesks/yellowdesks/hosts/index url is generated instead of /yellowdesks/hosts/index
                header("Location: " . $_REQUEST["redirect_url"]);
                exit(0);
            }
        }
    }
}
?>