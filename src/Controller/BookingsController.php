<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\Event\Event;

class BookingsController extends AppController {
    
    public function index() {
        if (!$this -> hasAccess([Roles::ADMIN])) return $this->redirect(["controller" => "users", "action" => "login", "redirect_url" =>  $_SERVER["REQUEST_URI"]]); 

        $model = TableRegistry::get('Bookings');
        $query = $model->find('all')->contain(['Hosts', 'Coworkers']);
        $this->set("rows", $query);
    }

    public function mybookings() {
        if (!$this -> hasAccess([Roles::COWORKER])) return $this->redirect(["controller" => "users", "action" => "login", "redirect_url" =>  $_SERVER["REQUEST_URI"]]); 

        $this->cleanupBookings();
        
        $model = TableRegistry::get('Bookings');

        $user = $this->getLoggedinUser();

        $query = $model->find('all')->order(["dt_inserted DESC"])->where(["coworker_id" => $user -> id])->contain(['Hosts', 'Coworkers']);
        $this->set("rows", $query);
    }
    
    public function invoice($id) {
        if (!$this -> hasAccess([Roles::COWORKER])) return $this->redirect(["controller" => "users", "action" => "login", "redirect_url" =>  $_SERVER["REQUEST_URI"]]); 

        $model = TableRegistry::get('Bookings');
        $query = $model->get($id, [
            'contain' => ['Hosts', 'Coworkers']
        ]);

        // todo: security: check if user is permitted to request this invoice
        
        $this->set("row", $query);
    }
    
    public function invoicehost($id) {
        if (!$this -> hasAccess([Roles::HOST])) return $this->redirect(["controller" => "users", "action" => "login", "redirect_url" =>  $_SERVER["REQUEST_URI"]]); 
 
        $model = TableRegistry::get('Bookings');
        $query = $model->find('all')->contain(['Coworkers'])->where(['host_id' => $id]);

        $hosts = TableRegistry::get('Hosts');
        $this->set("host", $hosts->get($id));

        // todo: security: check if user is permitted to request this invoice
        $this->set("rows", $query);
    }
    
    public function preparebookingrequest() {
        if (!$this -> hasAccess([Roles::COWORKER])) return $this->redirect(["controller" => "users", "action" => "login", "redirect_url" =>  $_SERVER["REQUEST_URI"]]); 
        
        
        
    }
    
    public function prepare($hostid, $begin, $end) {
        if (!$this -> hasAccess([Roles::COWORKER])) return $this->redirect(["controller" => "users", "action" => "login", "redirect_url" =>  $_SERVER["REQUEST_URI"]]); 
        $rets = [];
        
        $user = $this->getloggedinUser();

        $model = TableRegistry::get('Bookings');

        $model_hosts = TableRegistry::get('Hosts');
        $host = $model_hosts->get($hostid);


        $model_holidays = TableRegistry::get('Holidays');
        $holidays = $query = $model_holidays->find('all');



        $from = strtotime($begin);
        $to = strtotime($end);

        if ($to < $from) {
            // todo: was machen mit dem fall?
            $rets["debug_invalid date"] = "to < from, darf ned sein";
        }

        // todo: calculate products
        // zB 1x einzelticket, 1x 10er-block


        // todo: 6monate noch weichen und ggf. 2h-ticket noch weichen (wie?)
        
        $months = 0;
        do {
            $months++;

            // todo: 31.1. + 1 monat soll was ergeben? 31.2. gibts ned. irgendwas im februar? hmm. what?
            $test_date = mktime(date("H", $from), date("i", $from), date("s", $from), date("m", $from) + $months, date("d", $from), date("Y", $from));
        
        } while($test_date < $to);
        $months -= 1;
        $rets["debug_months"] = $months;

        $days = 0;
        $workingdays = [];
        do {
            $test_date = mktime(date("H", $from), date("i", $from), date("s", $from), date("m", $from) + $months, date("d", $from) + $days, date("Y", $from));
            $days++;

            if (date('N', $test_date) == 6 || date('N', $test_date) == 7 ) {
                // zu testender tag ist sat or sun
                continue;
            }

            $found=false;
            foreach ($holidays as $holiday) {
                if (date("Y-m-d", $test_date) == date("Y-m-d", strtotime($holiday->date))) {

                    $found = true;
                    break;
                }
            }
            if ($found)
                // zu testendender tag ist ein feiertag
                continue;

            array_push($workingdays, $test_date);
        } while($test_date < $to);
        $rets["debug_days"] = $days;
        $rets["debug_workingdays"] = sizeof($workingdays);

        $ticket_10 = (int)(sizeof($workingdays) / 10);
        $ticket_1 = sizeof($workingdays) % 10;
        $rets["debug_10erbloecke"] = $ticket_10;
        $rets["debug_einzeleintritte"] = $ticket_1;


        $bookings = [];
        foreach ($workingdays as $workingday) {
            $booking = [
                "type" => "Single Entry Ticket",
                "begin" => date("Y-m-d", $workingday),
                "end" => date("Y-m-d", $workingday),  
                "price" => $host->price_1day,
            ];
            array_push($bookings, $booking);
        }

        $total = 0;
        // todo: collision check
        foreach ($bookings as $booking) {
            $row = $this -> Bookings -> newEntity();
            $row -> coworker_id = $user -> id;
            $row -> payment_id = null;
            $row -> host_id = $hostid;
            $row -> description = $booking[ "type" ];
            $row -> price = $booking[ "price" ];
            $row -> servicefee_host = 0;
            $row -> servicefee_coworker = 0;
            $row -> vat = ($booking[ "price" ] / 100 * 20);
            $row -> begin = date("Y-m-d", strtotime($booking[ "begin" ]));
            $row -> end = date("Y-m-d", strtotime($booking[ "end" ]));

            if ($this -> Bookings -> save($row)) {
                $ret = [
                    "nickname" => $host -> nickname,
                    "host_id" => $host -> id,
                    "title" => $host -> title,
                    "price" => $row -> price,
                    "vat" => $row -> vat,
                    "description" => $booking[ "type" ],
                    "begin" => date("Y-m-d", strtotime($booking[ "begin" ])),
                    "end" => date("Y-m-d", strtotime($booking[ "end" ])),
                ];

                $rets[$row->id] = $ret;
                $total += $row -> price + $row -> vat;
            }

        }
        $rets["total"] = $total;

        //echo "<pre>";
        echo json_encode($rets, JSON_PRETTY_PRINT);
        exit();
    }
}
?>