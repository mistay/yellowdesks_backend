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
    
    public function loginappfb() {
        $ret = [];
        $ret["success"] = $this -> hasAccess([Roles::COWORKER]);
        if ( $ret["success"] ) {

            // todo: remove password field from result!!
            $loggedinuser = $this->getloggedInUser();
            $ret["loggedinuser"] = $loggedinuser;
        }
        echo json_encode($ret);
        exit();
    }
    
    function logout() {
        $this -> logoutSession();
        $this -> redirect('/');
    }
    
    public function home() {
    
    }

    public function register() {
        $this -> autoRender = false;
        $jsondata = $_REQUEST["data"];
        $data = json_decode($jsondata);

        var_dump($data);
        exit();
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
            
            // layout.ctp wurde schon gerendert, d.h. layout hat noch kein menu.
            // nochmal redirecten damit layout.ctp neu gerendert wird (dann mit menu, im eingloggten zustand)
            //$this->redirect(["controller" => "hosts", "action" => ""]);
            $this->redirect(["action" => "login"]);
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