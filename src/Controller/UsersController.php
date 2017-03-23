<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Mailer\Email;
use Cake\Routing\Router;

class UsersController extends AppController
{

    public function signupsuccess() {

    } 

    public function becomeahost() {
        if ($this -> request -> is('post')) {
            $data = $this -> request -> data;

            // sticky form
            $this->set("data", $data);

            if (trim($data["name"]) == "") {
                $this -> Flash -> success (__("This is a B2B service only. Please provide your companyname."));
                return;
            }

            if (trim($data["firstname"]) == "") {
                $this -> Flash -> success (__("Please provide your first name."));
                return;
            }

            if (trim($data["lastname"]) == "") {
                $this -> Flash -> success (__("Please provide your first name."));
                return;
            }

            if (strpos($data["email"], "@") === false) {
                $this -> Flash -> success (__("Please provide your e-mail address."));
                return;
            }

            if (trim($data["lastname"]) == "") {
                $this -> Flash -> success (__("Please provide your address."));
                return;
            }

            if (trim($data["postal_code"]) == "") {
                $this -> Flash -> success (__("Please provide your postal code."));
                return;
            }

            if (trim($data["city"]) == "") {
                $this -> Flash -> success (__("Please provide your city."));
                return;
            }

            if (strlen($data["password"]) < 8) {
                $this -> Flash -> success (__("Please make sure your password is at least 8 characters long."));
                return;
            }

            if (!isset($data["termsandconditions"])) {
                $this -> Flash -> success (__("Please aggree to our terms and conditions."));
                return;
            }

            if ((int)$data["desks"] <= 0) {
                $this -> Flash -> success (__("Please provide at least one desk."));
                return;
            }

            if (trim($data["title"]) == "") {
                $this -> Flash -> success (__("Please provide a sloagen, a title for your yellow desks."));
                return;
            }

            if (trim($data["details"]) == "") {
                $this -> Flash -> success (__("Please explain what's included for your coworker."));
                return;
            }

            if (trim($data["extras"]) == "") {
                $this -> Flash -> success (__("Please explain what's excluded for your coworker."));
                return;
            }

            $model = TableRegistry::get('Hosts');
            $row = $model -> newEntity();
            $data["username"] = $data["email"];
            $data["nickname"] = $data["firstname"];
            $model->patchEntity($row, $data);
            $model->save($row);


            $message = __($this -> appconfigs ["welcomemailhosts"], $row -> nickname);

            $email = new Email();
            $email -> setTransport('appdefault');
            $email
                ->setTemplate('default')
                ->setLayout('fancy')
                ->setEmailFormat('both')
                ->setTo('hello@yellowdesks.com')
                ->setFrom('office@langhofer.at')
                ->send($message);

            $this->redirect(["action" => "signupsuccess"]);
        }
    }

    public function signup() {
        if ($this -> request -> is('post')) {
            $data = $this -> request -> data;

            // sticky form
            $this->set("data", $data);

            if (trim($data["companyname"]) == "") {
                $this -> Flash -> success (__("This is a B2B service only. Please provide your companyname."));
                return;
            }

            if (trim($data["firstname"]) == "") {
                $this -> Flash -> success (__("Please provide your first name."));
                return;
            }

            if (trim($data["lastname"]) == "") {
                $this -> Flash -> success (__("Please provide your first name."));
                return;
            }

            if (strpos($data["email"], "@") === false) {
                $this -> Flash -> success (__("Please provide your e-mail address."));
                return;
            }

            if (strlen($data["password"]) < 8) {
                $this -> Flash -> success (__("Please make sure your password is at least 8 characters long."));
                return;
            }

            if (!isset($data["termsandconditions"])) {
                $this -> Flash -> success (__("Please aggree to our terms and conditions."));
                return;
            }

            $model = TableRegistry::get('Coworkers');
            $row = $model -> newEntity();
            $data["username"] = $data["email"];
            $model->patchEntity($row, $data);
            $model->save($row);

            $message = __($this -> appconfigs ["welcomemailcoworkers"], $row -> firstname);

            $email = new Email();
            $email -> setTransport('appdefault');
            $email
                ->setTemplate('default')
                ->setLayout('fancy')
                ->setEmailFormat('both')
                ->setTo('hello@yellowdesks.com')
                ->setFrom('office@langhofer.at')
                ->send($message);

            $this->redirect(["action" => "signupsuccess"]);
        }
    }

    public function resetpassword($email = "", $validatestring = "") {
        if (strlen($email) < 3) {
            $this -> Flash -> success (__("Please provide an E-Mail address with at least {0} characters.", 3));
            // todo: prevent controller from rendering view
            return;
        }
        if (strlen($validatestring) < 10) { // 10? how long are the hashes?
            // todo: prevent controller from rendering view
            $this -> Flash -> success (__("Please provide a validate string with at least {0} characters.", 10));
            return;
        }
        $user = $this -> searchUserByMail($email);
        
        if ($user -> count < 1) {
            $this -> Flash -> success (__("Sorry, no such E-Mail address found."));
            // todo: prevent controller from rendering view
            return;
        } elseif ($user -> count > 1) {
            $this -> Flash -> success (__("Sorry, more than one E-Mail address found. Please provide a unique E-Mail address."));
            // todo: prevent controller from rendering view
            return;
        } else {
            // genau eine e-mail adresse gefunden

            // user wants to set new password
            if (trim($user -> row -> passwordreset) != trim($validatestring)) {
                $this -> Flash -> success (__("Sorry, the provided validation string does not match any password reset request."));
                // todo: prevent controller from rendering view
                return;
            } else {
                if ($this -> request -> is('post')) {
                    $pass1 = $this->request->getData()["password1"];
                    $pass2 = $this->request->getData()["password2"];
                    
                    if ($pass1 == $pass2) {
                        $user -> row ["password"] = password_hash($pass1, PASSWORD_BCRYPT);
                        $user -> row ["passwordreset"] = null;
                        $user -> model -> save ($user -> row);
                        $this -> Flash -> success (__("Password updated successfully."));
                    } else {
                        $this -> Flash -> success (__("Passwords do not match."));
                    }
                }
            }
        }
    }

    private function searchUserByMail($email) {
        //$ret = stdClass();
        $modelCoworkers = TableRegistry::get('Coworkers');
        $modelHosts = TableRegistry::get('Hosts');
        
        $query = $modelCoworkers -> find('all') -> where(["Coworkers.email LIKE" => '%' . $email . '%']);
        $coworkers = $query->toArray();
        
        //todo: create std class object
        @$ret -> numCoworkers = sizeof($coworkers);

        $query = $modelHosts -> find('all') -> where(["Hosts.email LIKE" => '%' . $email . '%']);
        $hosts = $query->toArray();
        $ret -> numHosts = sizeof($hosts);

        $ret -> count = $ret -> numCoworkers + $ret -> numHosts;

        if ($ret -> numCoworkers + $ret -> numHosts == 1) {
            $ret -> model = $ret -> numCoworkers == 1 ? $modelCoworkers : $modelHosts;
            $ret -> row =  $ret -> numCoworkers == 1 ? $coworkers[0] : $hosts[0];
        }
        return $ret;
    }

    public function forgotpassword($email = "", $validatestring = "") {
        if ($this -> request -> is('post')) {
            $email = $this -> request -> data['email'];

            if (strlen($email) < 3) {
                $this -> Flash -> success (__("Please provide an E-Mail address with at least {0} characters.", 3));
                return;
            }
            $user = $this -> searchUserByMail($email);
            
            if ($user -> count < 1) {
                $this -> Flash -> success (__("Sorry, no such E-Mail address found."));
                return;
            } elseif ($user -> count > 1) {
                $this -> Flash -> success (__("Sorry, more than one E-Mail address found. Please provide a unique E-Mail address."));
                return;
            } else {
                // genau eine e-mail adresse gefunden

                $this -> Flash -> success (__("We sent you an email containing instructions for resetting your password. Please check your inbox."));
                $user -> row["passwordreset"] = base64_encode(rand());
                $user -> model -> save ($user -> row);
                $this -> sendRecoverInstructions($user -> row ["email"], $user -> row["passwordreset"]);
                
            }
        }
    }

    private function sendRecoverInstructions($to, $hash) {
        $reseturl =  Router::url([ 
            'controller' => 'Users','action' => 'resetpassword',
            $to,
            $hash,
            ], true);

        $message = __("Your E-mail has been reset. Please navigate to {0} to reset your password.", $reseturl);

        $email = new Email();
        $email -> setTransport('appdefault');
        $email
            ->setTemplate('default')
            ->setLayout('fancy')
            ->setEmailFormat('both')
            ->setTo('hello@yellowdesks.com')
            ->setFrom('office@langhofer.at')
            ->send($message);
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

        if ($loggedinuser == null) {
            $this -> redirect(["controller" => "users", "action" => "login"]);
            return;
        }

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
            
            $this->basicauth();
            if ($this -> getLoggedInUser() != null) {
                $this->redirect(["action" => "welcome"]);
            }
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