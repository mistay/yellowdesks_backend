<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;

class HostsController extends AppController {
    
    public function index() {
        $model = TableRegistry::get('Hosts');
        $query = $model->find('all')->contain(['Pictures', 'Payments']);
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
}
?>