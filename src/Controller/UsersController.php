<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

class UsersController extends AppController
{
    public function index()
    {
        $this->set('users', $this->Users->find('all'));
    }

    public function view($id)
    {
        if (!$this -> hasAccess([Roles::ADMIN])) return $this->redirect(["controller" => "users", "action" => "login", "redirect_url" =>  $_SERVER["REQUEST_URI"]]); 
        $user = $this->Users->get($id);
        $this->set(compact('user'));
    }

    public function add() {
       if (!$this -> hasAccess([Roles::ADMIN])) return $this->redirect(["controller" => "users", "action" => "login", "redirect_url" =>  $_SERVER["REQUEST_URI"]]); 
       
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->data);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(['action' => 'add']);
            }
            $this->Flash->error(__('Unable to add the user.'));
        }
        $this->set('user', $user);
    }
    
    public function loginappfb($input_token) {
        if (!$this -> hasAccess([Roles::COWORKER])) return $this->redirect(["controller" => "users", "action" => "login", "redirect_url" =>  $_SERVER["REQUEST_URI"]]); 
        $ret = [];
        $ret["success"] = false;
        
        // https://developers.facebook.com/docs/accountkit/graphapi
        // https://developers.facebook.com/tools/accesstoken/ "yellowdesk" app
        
        // e.g. curl "https://graph.facebook.com/debug_token?input_token=EAAEZBMYKYIyQBALZCq1hcvvZCsoNNCXkpx8fRpXkwms36q3u7NAA9y9a9ZB7ew3PWJLj7ZBtczlZCdwkZCUOE3BhHxfhtgPLtjLOhBM3DWyOQP4bRjXs6HrNAZBIKXi4t80bRUxG6zUnccbgrPaPIyVQczZArEZAc7pmwLkGXTBTQ1bZC0CozlEqZAMGKecyevmmuP5jE0LYNF8JDiAyWY1eSNd3&access_token=349857342038820|ysb4EckVxJBJGuChffSWH-VLbfA"
        // {"data":{"app_id":"349857342038820","application":"yellowdesk","expires_at":1492640267,"is_valid":true,"issued_at":1487456267,"scopes":["email","public_profile"],"user_id":"673726606120086"}}
        
        // query e-mail address
        //curl "https://graph.facebook.com/me?fields=email&access_token=EAAEZBMYKYIyQBALZCq1hcvvZCsoNNCXkpx8fRpXkwms36q3u7NAA9y9a9ZB7ew3PWJLj7ZBtczlZCdwkZCUOE3BhHxfhtgPLtjLOhBM3DWyOQP4bRjXs6HrNAZBIKXi4t80bRUxG6zUnccbgrPaPIyVQczZArEZAc7pmwLkGXTBTQ1bZC0CozlEqZAMGKecyevmmuP5jE0LYNF8JDiAyWY1eSNd3"
        
        
        //$url = "https://graph.facebook.com/debug_token?input_token=" . $input_token . "&access_token=349857342038820|ysb4EckVxJBJGuChffSWH-VLbfA";
        $url = "https://graph.facebook.com/me?fields=email&access_token=" . $input_token;
        $fb_result_json = file_get_contents($url);
        $fb_result = json_decode($fb_result_json);
        //print_r($fb_result);
        
        
        $fb_result_json = file_get_contents($url);
        $fb_result = json_decode($fb_result_json);
            
        if ($fb_result->email != "") {
            $ret["success"] = true;
        }
        
        // todo: provide login details
        // todo: proof login against db
        
        echo json_encode($ret);
        exit();
    }
    
    /*
    public function login()
    {
        if (stripos(@$_REQUEST["format"], "json") !== false || stripos(strtolower($_SERVER['HTTP_USER_AGENT']),'android') !== false) {
            if (@$_REQUEST["format"] == "jsonbrowser") echo "<pre>";
            $ret = [];
            
            $ret["error"] = "no username or no password specified in request. please provide username/password combination.";
            $user = $this->Auth->identify();
            //if ($user) { //TODO
                $ret["error"] = "";
                $ret["username"] = "armin"; // todo: überlegen wie user/cowoker/hosts in db???!?!?
                $ret["firstname"] = "armin";
                $ret["lastname"] = "langhofer";
            //} else {
                //$ret["error"] = "username or password invalid";
            //}
            
            echo json_encode($ret, JSON_PRETTY_PRINT);
            if (@$_REQUEST["format"] == "jsonbrowser") echo "</pre>";
            exit();
        }
        
        
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);
                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Flash->error(__('Invalid username or password, try again'));
        }
    }*/

    function logout() {
        $this -> logoutSession();
        $this -> redirect('/');
    }
    
    public function home() {
    
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
        
        echo json_encode($ret);
        exit();
        
    }
    
    function login() {
        $this->basicauth();
        if ($this -> getLoggedInUser() != null) {
            // user logged in
            
            //$target = $this -> initMenu(true);
            //$this -> redirect($target);
        }
        //var_dump($this -> getLoggedInUser());
        $success = null;
        
        if ($this->request->is('post')) {
            $username = $this -> request -> data['username'];
            $password = $this -> request -> data['password'];

            $this -> auth($username, $password);
        }
        //var_dump($this -> getLoggedInUser());
        
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