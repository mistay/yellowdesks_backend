<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

class PicturesController extends AppController {
    
    public $paginate = [
        'limit' => 100,
        'order' => [
            'Host.id' => 'asc'
        ]
    ];
    
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Paginator');
    }
    
    public function cru() {
        $model = TableRegistry::get('Hosts');
        $query = $model->find('all');
        $this->set("rows", $query);
        
        $data = $this->request->getData();
        if (!empty($data)) {
            
            //print_r($data["files"]);
            foreach ($data["files"] as $file) {
                $model2 = TableRegistry::get('Pictures');
                $row = $model2->newEntity();
                
                $row->mime=$file["type"];
                $row->name="helloworld";
                $row->host_id=5;
                //print_r($row);
                $row->data = file_get_contents($file["tmp_name"]);
                //print_r($row);
                $succ = $model2->save($row);
                //echo "succ"; var_dump($succ);
                
                // prevent DUPes by browser reload
                $this->redirect(["action" => "cru"]);
                
            }
        }
    }
    
    public function index() {
        if (!$this -> hasAccess([Roles::ADMIN])) return $this->redirect(["controller" => "users", "action" => "login", "redirect_url" =>  $_SERVER["REQUEST_URI"]]); 
        $model = TableRegistry::get('Pictures');
        
        $where = isset($_REQUEST["host_id"]) ? ['Hosts.id' => $_REQUEST["host_id"]] : [];  

        $query = $model->find('all')->where($where)->contain(['Hosts']);
        $this->set("rows", $this->paginate($query));
        
        
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
        if (!$this -> hasAccess([Roles::ADMIN, Roles::COWORKER])) return $this->redirect(["controller" => "users", "action" => "login", "redirect_url" =>  $_SERVER["REQUEST_URI"]]); 
        $this->autoRender=false;
        
        $id = (int) $unsafe_id;
        
        $model = TableRegistry::get('Pictures');
        $query = $model->get($id);
        $data = stream_get_contents($query->data);
        
        
        $virtualfilename = 'data://text/plain;base64,' . base64_encode($data);
        
        $exif = null;
        $ort = 0;
        try {
            // kann zB bei pngs exif_read_data(H+VE2DH4J30BAAAAAElFTkSuQmCC): File not supported 
            // werfen. deshalb mit @ .. todo: sauberer lösen?
            $exif = @exif_read_data ( $virtualfilename );
        } catch (Exception $e) {
        }
        
        //$ort = $exif['IFD0']['Orientation'];
        $ort = ($exif != null && isset($exif['Orientation'])) ? $exif['Orientation'] : 0;
        
        $degrees=0;
        $flip=0;
        switch($ort)
        {
            case 1: /*nothing */ break;
            case 2: $flip = IMG_FLIP_HORIZONTAL;                    break;
            case 3:                                 $degrees = 180; break;
            case 4: $flip = IMG_FLIP_VERTICAL;                      break;
            case 5: $flip = IMG_FLIP_VERTICAL;      $degrees = -90; break;
            case 6:                                 $degrees = -90; break;
            case 7: $flip = IMG_FLIP_HORIZONTAL;    $degrees = -90; break;
            case 8:                                 $degrees = 90;  break;
        }
            
            
        // e.g. /pictures/get?resolution=320x240
        if (isset($_REQUEST["resolution"])) {
            $src_img = imagecreatefromstring($data);
            $src_w = imagesx($src_img);
            $src_h = imagesy($src_img);
            $ratio = $src_w / $src_h;
            
            list($dst_w, $dst_h) = explode("x", $_REQUEST["resolution"]);
            
            // http://stackoverflow.com/questions/6594089/calculating-image-size-ratio-for-resizing
            $srcX = $srcY = 0;
            if (isset($_REQUEST["crop"])) {
                if ($ratio < 1) {
                    $srcY = ($src_h / 2) - ($src_w / 2);
                    $src_h = $src_w;
                } else {
                    $srcX = ($src_w / 2) - ($src_h / 2);
                    $src_w = $src_h;
                }
            } else {
                $dst_w = $dst_h = min(max($dst_w, $dst_h), max($src_w, $src_h));
                if ($ratio < 1)     $dst_w = $dst_h * $ratio;
                else                $dst_h = $dst_w / $ratio;
            }

            $dst_image = imagecreatetruecolor($dst_w, $dst_h);
            imagecopyresized ($dst_image, $src_img , 0, 0, $srcX, $srcY, $dst_w, $dst_h , $src_w, $src_h);
            
            if ($degrees != 0)
                $dst_image = imagerotate($dst_image, $degrees, 0);
            
            if ($flip > 0)
                imageflip ( $image, $flip );
            
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