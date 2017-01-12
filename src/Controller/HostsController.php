<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

class HostsController extends AppController {
    
    //public $uses = array("Orderbutton");

    public function index() {
        $model = TableRegistry::get('Hosts');
        $query = $model->find('all');
        $this->set("rows", $query);
        
        if (stripos(@$_REQUEST["format"], "json") !== false || stripos(strtolower($_SERVER['HTTP_USER_AGENT']),'android') !== false) {
            $rows = $query->toArray();
            if (@$_REQUEST["format"] == "jsonbrowser") echo "<pre>";
            
            $ret = [];
            foreach ($rows as $row) {
                array_push($ret,
                        [   "id" => $row-> id,
                            "host" => $row->nickname,
                            "desks" => $row->desks,
                            "desks_avail" => $row->desks, //todo
                            "imageURL" => "http://langhofer.net/yellowdesks/alex.png",
                            "details" => $row->details,
                            "title" => $row->title,
                            "lat" => $row->lat,
                            "lng" => $row->lng,
                        ]);
            }
            
            echo json_encode($ret, JSON_PRETTY_PRINT);
            if (@$_REQUEST["format"] == "jsonbrowser") echo "</pre>";
            exit();
        }
        
    }
}
?>