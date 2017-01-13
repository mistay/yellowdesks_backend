<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

class PicturesController extends AppController {
    
    //public $uses = array("Orderbutton");

    public function index() {
        $model = TableRegistry::get('Pictures');
        $query = $model->find('all')->contain(['Hosts']);
        $this->set("rows", $query);
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