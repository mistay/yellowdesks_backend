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
}
?>