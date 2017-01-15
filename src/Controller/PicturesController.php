<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

class PicturesController extends AppController {
    
    //public $uses = array("Orderbutton");

    public function index() {
        $model = TableRegistry::get('Pictures');
        
        $where = isset($_REQUEST["host_id"]) ? ['Hosts.id' => $_REQUEST["host_id"]] : [];  

        $query = $model->find('all')->where($where)->contain(['Hosts']);
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
        $data = stream_get_contents($query->data);
        
        // e.g. /pictures/get?resolution=320x240
        if (isset($_REQUEST["resolution"])) {
            $src_img = imagecreatefromstring($data);
            $src_w = imagesx($src_img);
            $src_h = imagesy($src_img);
            
            list ($max_width, $max_height) = explode("x", $_REQUEST["resolution"]);
            
            // taller
            if ($src_h > $max_height) {
                $dst_w = ($max_height / $src_h) * $src_w;
                $dst_h = $max_height;
            }

            // wider
            if ($src_w > $max_width) {
                $dst_h = ($max_width / $src_w) * $src_h;
                $dst_w = $max_width;
            }
            
            $dst_image = imagecreatetruecolor($dst_w, $dst_h);

            imagecopyresized ($dst_image, $src_img , 0, 0, 0, 0, $dst_w, $dst_h , $src_w, $src_h);
            
            header("Content-Type: image/jpeg");
            imagejpeg ($dst_image);
        } else {
            header("Content-Type: " . $query->mime);
            print_r($data);
            
        }
        
        
        exit(0);
    }
}
?>