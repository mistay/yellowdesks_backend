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
        $this->Auth->allow(['index', 'getworkingdays', 'getprice']);
    }
    
    public function index() {
        $model = TableRegistry::get('Holidays');
        $query = $model->find('all');
        $this->set("rows", $query);
    }
    
    public function getprice($unsafe_host_id, $unsafe_begin, $unsafe_end) {
        // todo: bei 11 tagen zB 1x10er block + 1x einzelticket und nicht dividieren!
        $host_id = (int) $unsafe_host_id;
        
        $model = TableRegistry::get('Hosts');
        $query = $model->get($host_id);
        $query->price_1day;
        
        $model2 = TableRegistry::get('Holidays');
        $workingdays = $model2->getworkingdays($unsafe_begin, $unsafe_end);
        
        $days = $workingdays["count"];
        if ($workingdays["calendardays"] >= 6*31)
            $price = $query->price_6months * $workingdays["calendardays"]/(31*6);
        else if ($workingdays["calendardays"] >= 31)
            $price = $query->price_1month * $workingdays["calendardays"]/31;
        else if ($workingdays["calendardays"] >= 10)
            $price = $query->price_10days * $days/10;
        else if ($workingdays["calendardays"] > 0)
            $price = $query->price_1day * $days;
        
        $workingdays["price"] = $price;
        echo json_encode($workingdays, JSON_PRETTY_PRINT);
        exit();
    }
    
    
}
?>