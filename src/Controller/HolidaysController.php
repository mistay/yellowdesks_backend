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
        $this->Auth->allow(['index', 'getworkingdays']);
    }
    
    
    public function index() {
        $model = TableRegistry::get('Holidays');
        $query = $model->find('all');
        $this->set("rows", $query);
    }
    
    public function getworkingdays($unsafe_begin, $unsafe_end) {
        $ret = [];
        
        $count = 0;
        $ret["count"] = $count;
        $ret["details"] = "";
        
        $model = TableRegistry::get('Holidays');
        $query = $model->find('all');
        $this->set("rows", $query);
        
        
        $begin = new \Datetime($unsafe_begin);
        $end = new \Datetime($unsafe_end);
        $end = $end->modify( '+1 day' ); 

        $interval = new \DateInterval('P1D');
        $daterange = new \DatePeriod($begin, $interval ,$end);

        
        foreach($daterange as $date){
            $ret["details"] .= $date->format("Y-m-d: ") . $date->format("Ymd") . ": ";

            if (date('N', $date->getTimestamp()) >= 6) {
                $ret["details"] .= "weekend\n";
                continue;
            }
            
            foreach ($query as $row) {
                if ($date->format("Ymd") == date ("Ymd", strtotime($row->date))) {
                    // holiday
                    $ret["details"] .= "holiday\n";
                    continue 2;
                }
            }
            $ret["count"]++;
            $ret["details"] .= "working day\n";
        }
        
        echo json_encode($ret, JSON_PRETTY_PRINT);
        exit();
    }
}
?>