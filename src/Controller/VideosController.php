<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Event\Event;

class VideosController extends AppController {
    
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        //todo: secure me, only needed for app
        $this->Auth->allow(['get']);
    }
    
    
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
    
    public function index() {
        $model = TableRegistry::get('Videos');
        
        $where = isset($_REQUEST["host_id"]) ? ['Hosts.id' => $_REQUEST["host_id"]] : [];  

        $query = $model->find('all')->where($where)->contain(['Hosts']);
        $this->set("rows", $this->paginate($query));
        
        
        // e.g. http://localhost:8888/yellowdesks/videos?host_id=5&format=jsonbrowser
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
                            "url" => "https://yellowdesks.com/videos/" . $row->url),
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
        
        $model = TableRegistry::get('Videos');
        $query = $model->get($id);
        //$data = stream_get_contents($query->data);
        
        header("Content-Type: video/mp4");
        echo $data;

        exit(0);
    }
}
?>