<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\Event\Event;

class HostsController extends AppController {
    
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        //todo: secure me, only needed for app
        $this->Auth->allow(['index']);
    }
    
    
    // todo: request device information (display size) and send imageURL with correct resolution
    // e.g. /pictures/get/311?resolution=100x100&crop=true instead of /pictrues/get/311
    public function index() {
        $model = TableRegistry::get('Hosts');
        $query = $model->find('all')->contain(['Pictures', 'Payments', 'Videos']);
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
                            "price_1day" => $row->price_1day,
                            "price_10days" => $row->price_10days,
                            "price_1month" => $row->price_1month,
                            "price_6months" => $row->price_6months,
                            "title" => $row->title,
                            "videoURL" => (sizeof($row->videos) > 0 ? Router::url(['controller' => 'videos', 'action' => '', $row->videos[0]->url], true) : null),
                            
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