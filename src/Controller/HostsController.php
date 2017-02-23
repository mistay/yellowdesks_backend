<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\Event\Event;

class HostsController extends AppController {
    
    public function cru($unsafe_id=null) {
        if (!$this -> hasAccess([Roles::ADMIN])) return $this->redirect(["controller" => "users", "action" => "login", "redirect_url" =>  $_SERVER["REQUEST_URI"]]); 
        
        $model = TableRegistry::get('Hosts');
        $id=(int)$unsafe_id;
        $row = [];
        if ($id>0) {
            $row = $model->get($id);
        } else {
            $row = $model->newEntity();
        }
        $this->set("row", $row);
        if (!empty($this->request->getData())) {
            $model->patchEntity($row, $this->request->getData());
        //else
        //    $row = $model->newEntity();

            $model->save($row);
            return $this->redirect(['action' => 'index']);
        }
    }
    
    public function delete($unsafe_id) {
        if (!$this -> hasAccess([Roles::ADMIN])) return $this->redirect(["controller" => "users", "action" => "login", "redirect_url" =>  $_SERVER["REQUEST_URI"]]); 
        
        $model = TableRegistry::get('Hosts');
        $row = $model->get($unsafe_id);
        $result = $model->delete($row);
        
        return $this->redirect(['action' => 'index']);
    }
    
    public function calclatlngloose() {
        if (!$this -> hasAccess([Roles::ADMIN])) return $this->redirect(["controller" => "users", "action" => "login", "redirect_url" =>  $_SERVER["REQUEST_URI"]]); 
        
        $model = TableRegistry::get('Hosts');
        $query = $model->find('all')->where(['lat_loose is' => null, 'lng_loose is' => null,]);

        foreach ($query as $row) {
            $row->lat_loose = $row->lat + (mt_rand(-1000,1000) / 1000000.0);
            $row->lng_loose = $row->lng + (mt_rand(-1000,1000) / 1000000.0);
            print_r($row);
            $model->save($row);
        }
        exit();
    }
    
    // todo: request device information (display size) and send imageURL with correct resolution
    // e.g. /pictures/get/311?resolution=100x100&crop=true instead of /pictrues/get/311
    public function index() {
        if (!$this -> hasAccess([Roles::ADMIN])) return $this->redirect(["controller" => "users", "action" => "login", "redirect_url" =>  $_SERVER["REQUEST_URI"]]); 
        
        $model = TableRegistry::get('Hosts');
        $query = $model->find('all')->contain(['Pictures'=> function ($q) {
                                                               return $q
                                                                    ->select(['id', 'host_id']);
                                                            },
                                               'Payments', 'Videos']);
        $this->set("rows", $query);
        
        $model = TableRegistry::get('Logs');
        $row = $model->newEntity();
        $row->message = print_r($_REQUEST, true) .  print_r($_SERVER, true);

        if ($model->save($row)) {
        }
        
        if (stripos(@$_REQUEST["format"], "json") !== false || stripos(strtolower($_SERVER['HTTP_USER_AGENT']),'android') !== false) {
            $rows = $query->toArray();
            if (@$_REQUEST["format"] == "jsonbrowser") echo "<pre>";
            $ret = [];
            foreach ($rows as $row) {
                
                $pictures = [];
                foreach ($row->pictures as $picture) {
                    array_push($pictures, Router::url(['controller' => 'pictures', 'action' => 'get', $picture->id, 'resolution' => '600x'], true));
                }
                
                array_push($ret,
                        [   "id" => $row-> id,
                            "host" => $row->nickname,
                            "desks" => $row->desks,
                            "desks_avail" => $row->desks,
                            "picture_id" => $row->picture_id,
                            "imageURL" => ($row->picture_id > 0 ? Router::url(['controller' => 'pictures', 'action' => 'get', $row->picture_id, 'resolution' => '600x'], true) : null),
                            "imageURLs" => $pictures,
                            "details" => $row->details,
                            "extras" => $row->extras,
                            "open_from" => $row->open_from == null ? null : date("H:i:s", strtotime($row->open_from)),
                            "open_till" => $row->open_till == null ? null : date("H:i:s", strtotime($row->open_till)),
                            "open_247fixworkers" => $row->open_247fixworkers,
                            "price_1day" => $row->price_1day,
                            "price_10days" => $row->price_10days,
                            "price_1month" => $row->price_1month,
                            "price_6months" => $row->price_6months,
                            "title" => $row->title,
                            "videoURL" => (sizeof($row->videos) > 0 ? Router::url(['controller' => 'videos', 'action' => '', $row->videos[0]->url], true) : null),
                            
                            // todo: in db schreiben damit nicht immer frische werte kommen (sonst könnte man lat & lng reversen)
                            "lat" => $row->lat_loose,
                            "lng" => $row->lng_loose,
                        ]);
            }
            
            echo json_encode($ret, JSON_PRETTY_PRINT);
            if (@$_REQUEST["format"] == "jsonbrowser") echo "</pre>";
            exit();
        }
        
    }
}
?>