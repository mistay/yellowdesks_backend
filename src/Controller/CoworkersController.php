<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;

class CoworkersController extends AppController {
    public function index() {
        if (!$this -> hasAccess([Roles::ADMIN])) return $this->redirect(["controller" => "users", "action" => "login", "redirect_url" =>  $_SERVER["REQUEST_URI"]]); 

        $model = TableRegistry::get('Coworkers');
        $query = $model->find('all'); //->contain(['Pictures']);
        $this->set("rows", $query);
        
        if (stripos(@$_REQUEST["format"], "json") !== false || stripos(strtolower($_SERVER['HTTP_USER_AGENT']),'android') !== false) {
            $rows = $query->toArray();
            if (@$_REQUEST["format"] == "jsonbrowser") echo "<pre>";
            $ret = [];
            foreach ($rows as $row) {
                
                $pictures = [];
                foreach ($row->pictures as $picture) {
                    array_push($pictures, Router::url(['controller' => 'pictures', 'action' => 'get', $picture->id], true));
                }
                
                array_push($ret,
                        [   "id" => $row-> id,
                            "host" => $row->nickname,
                            "desks" => $row->desks,
                            "desks_avail" => $row->desks,
                            "imageURL" => ($row->picture_id > 0 ? Router::url(['controller' => 'pictures', 'action' => 'get', $row->picture_id], true) : null),
                            "images" => $pictures,
                            "details" => $row->details,
                            "title" => $row->title,
                         
                            // todo: in db schreiben damit nicht immer frische werte kommen (sonst könnte man lat & lng reversen)
                            "lat" => $row->lat + (mt_rand(-1000,1000) / 1000000.0),
                            "lng" => $row->lng + (mt_rand(-1000,1000) / 1000000.0),
                        ]);
            }
            
            echo json_encode($ret, JSON_PRETTY_PRINT);
            if (@$_REQUEST["format"] == "jsonbrowser") echo "</pre>";
            exit();
        }
    }
    
    public function cru($unsafe_id=null) {
        if (!$this -> hasAccess([Roles::ADMIN, Roles::COWORKER])) return $this->redirect(["controller" => "users", "action" => "login", "redirect_url" =>  $_SERVER["REQUEST_URI"]]); 
        
        $model = TableRegistry::get('Coworkers');
        
        $user = $this->getloggedInUser();
        
        if ($user->role==Roles::ADMIN)
            $id=(int)$unsafe_id;
        
        if ($user->role==Roles::COWORKER)
            $id = $user -> id;
        
        
        $row = [];
        if ($id>0) {
            $row = $model->get($id);
        } else {
            $row = $model->newEntity();
        }
        $this->set("row", $row);
        if (!empty($this->request->getData())) {
            $model->patchEntity($row, $this->request->getData());
            
            //$row->password = md5($row->username . $this->request->getData()->password);
            
            $model->save($row);
            $this->Flash->set('Successfully saved.');
            //return $this->redirect(['action' => 'cru']);
        }
    }
    
    public function changepass($unsafe_id) {
        if (!$this -> hasAccess([Roles::ADMIN, Roles::COWORKER])) return $this->redirect(["controller" => "users", "action" => "login", "redirect_url" =>  $_SERVER["REQUEST_URI"]]);
        
        $user = $this->getloggedInUser();
        
        if ($user->role==Roles::ADMIN)
            $id=(int)$unsafe_id;
        
        if ($user->role==Roles::COWORKER)
            $id = $user -> id;
        
        $model = TableRegistry::get('Coworkers');
        $row = $model->get($unsafe_id);
        $this->set("row", $row);
        
        if (!empty($this->request->getData())) {
            $model->patchEntity($row, $this->request->getData());
            
            $pass1 = $this->request->getData()["password1"];
            $pass2 = $this->request->getData()["password2"];
            
            if ($pass1 == $pass2) {
                
                $row->password = md5($row->username . $pass1);
                
                if ($model->save($row)) {
                    $this->Flash->set('Password successfully set.');
                    return $this->redirect(['action' => 'cru', $id]);
                } else {
                    $this->Flash->error(__('Database Error: Could not save data.'));
                }
            } else {
                $this->Flash->error(__('Passwords do not match, please correct.'));
            }
        }
    }
    
    public function delete($unsafe_id) {
        if (!$this -> hasAccess([Roles::ADMIN])) return $this->redirect(["controller" => "users", "action" => "login", "redirect_url" =>  $_SERVER["REQUEST_URI"]]); 
        
        $model = TableRegistry::get('Coworkers');
        $row = $model->get($unsafe_id);
        $result = $model->delete($row);
        
        return $this->redirect(['action' => 'index']);
    }
}
?>