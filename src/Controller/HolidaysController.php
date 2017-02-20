<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\Event\Event;

class HolidaysController extends AppController {
    
    
    public function foo() {
        $list_json = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/list.json");
        
        
        $list = json_decode($list_json);
        
        $ret = json_encode($list, JSON_PRETTY_PRINT);
        
        //print_r($list);
        echo $ret;
        
        
        
        exit();
        
    }
    
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        //todo: secure me, only needed for app
        $this->Auth->allow(['index', 'getworkingdays', 'getprice']);
    }
    
    
    public function index() {
        $model = TableRegistry::get('Holidays');
        $query = $model->find('all');
        $this->set("rows", $query);
    }
    
    public function getprice($unsafe_host_id, $unsafe_begin, $unsafe_end) {
        $host_id = (int) $unsafe_host_id;
        
        $model = TableRegistry::get('Hosts');
        $query = $model->get($host_id);
        $query->price_1day;
        
        $model2 = TableRegistry::get('Holidays');
        $workingdays = $model2->getworkingdays($unsafe_begin, $unsafe_end);
        
        $days = $workingdays["count"];
        if ($days >= 6*31)
            $price = $query->price_6months * $workingdays["calendardays"]/(31*6);
        else if ($days >= 31)
            $price = $query->price_1month * $workingdays["calendardays"]/31;
        else if ($days >= 10)
            $price = $query->price_10days * $days/10;
        else if ($days > 0)
            $price = $query->price_1day * $days;
        
        $workingdays["price"] = $price;
        echo json_encode($workingdays, JSON_PRETTY_PRINT);
        exit();
    }
    
    
}
?>