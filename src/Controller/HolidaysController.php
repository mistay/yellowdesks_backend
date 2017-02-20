<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\Event\Event;

class HolidaysController extends AppController {
    
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        //todo: secure me, only needed for app
        $this->Auth->allow(['index']);
    }
    
    
    public function index() {
        $model = TableRegistry::get('Holidays');
        $query = $model->find('all');
        $this->set("rows", $query);
    }
}
?>