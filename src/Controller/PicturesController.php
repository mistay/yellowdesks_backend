<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

class PicturesController extends AppController {
    
    //public $uses = array("Orderbutton");

    public function index() {
        $model = TableRegistry::get('Pictures');
        
        $query = $model->find('all')->contain(['Hosts']);
        if (@$_REQUEST["host_id"] > 0) {
            $host_id = (int) $_REQUEST["host_id"];
            if ($host_id > 0)
                $query = $model->find('all')->where(['Hosts.id' => $host_id])->contain(['Hosts']);
        }
        $this->set("rows", $query);
        
        
        // e.g. http://localhost:8888/yellowdesks/pictures?host_id=5&format=jsonbrowser
        /*
        [
            {
                "id": 1,
                "name": "standdesk",
                "mime": "image\/jpeg",
                "data": "<base64string...>"
            },
            {
                "id": 2,
                "name": "restroom",
                "mime": "image\/jpeg",
                "data": "<base64string...>"
           }
        ]
        */
        if (stripos(@$_REQUEST["format"], "json") !== false || stripos(strtolower($_SERVER['HTTP_USER_AGENT']),'android') !== false) {
            $rows = $query->toArray();
            if (@$_REQUEST["format"] == "jsonbrowser") echo "<pre>";
            $ret = [];
            foreach ($rows as $row) {
                array_push($ret,
                        [   "id" => $row->id,
                            "name" => $row->name,
                            "mime" => $row->mime,
                            "data" => base64_encode(stream_get_contents($row->data)),
                        ]);
                
            }
            echo json_encode($ret, JSON_PRETTY_PRINT);
            if (@$_REQUEST["format"] == "jsonbrowser") echo "</pre>";
            exit();
        }
    }
    
    public function get($unsafe_id) {
        $this->autoRender=false;
        
        $id = (int) $unsafe_id;
        
        $model = TableRegistry::get('Pictures');
        $query = $model->get($id);
        $bla = stream_get_contents($query->data);
        header("Content-Type: " . $query->mime);
        print_r($bla);
        exit(0);
    }
}
?>