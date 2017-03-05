<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\Event\Event;

class BookingsController extends AppController {
    
    public function index() {
        if (!$this -> hasAccess([Roles::COWORKER, Roles::ADMIN])) return $this->redirect(["controller" => "users", "action" => "login", "redirect_url" =>  $_SERVER["REQUEST_URI"]]); 

        $model = TableRegistry::get('Bookings');

        $where = [];
        $user = $this -> getLoggedinUser();
        if ($user -> role == Roles::COWORKER)
            $where = ["coworker_id" => $user -> id];


        $query = $model->find('all')->where($where)->contain(['Hosts', 'Coworkers']);
        $this->set("rows", $query);
        
        if (stripos(@$_REQUEST["format"], "json") !== false || stripos(strtolower($_SERVER['HTTP_USER_AGENT']),'android') !== false) {
            $rows = $query->toArray();
            if (@$_REQUEST["format"] == "jsonbrowser") echo "<pre>";
            $ret = [];
            foreach ($rows as $row) {
                
                $pictures = [];
                foreach ($row->pictures as $picture) {
                    array_push($pictures, Router::url(['controller' => 'pictures', 'action' => 'get', $picture->id], true));
                }
                
                array_push($ret,
                        [   "id" => $row-> id,
                            "host" => $row->nickname,
                            "desks" => $row->desks,
                            "desks_avail" => $row->desks,
                            "imageURL" => ($row->picture_id > 0 ? Router::url(['controller' => 'pictures', 'action' => 'get', $row->picture_id], true) : null),
                            "images" => $pictures,
                            "details" => $row->details,
                            "title" => $row->title,
                         
                            // todo: in db schreiben damit nicht immer frische werte kommen (sonst könnte man lat & lng reversen)
                            "lat" => $row->lat + (mt_rand(-1000,1000) / 1000000.0),
                            "lng" => $row->lng + (mt_rand(-1000,1000) / 1000000.0),
                        ]);
            }
            
            echo json_encode($ret, JSON_PRETTY_PRINT);
            if (@$_REQUEST["format"] == "jsonbrowser") echo "</pre>";
            exit();
        }
        
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
        
        $user = $this->getloggedinUser();

        $model = TableRegistry::get('Bookings');

        $model_hosts = TableRegistry::get('Hosts');
        $hosts = $query = $model_hosts->find('all');


        $from = strtotime($begin);
        $to = strtotime($end);

        // todo: calculate products
        // zB 1x einzelticket, 1x 10er-block

        $bookings = [
            [   "type" => "10 Entries Ticket",
                "begin" => "2010-03-03",
                "end" => "2010-03-15",  
                "price" => .10,
            ],
            [   "type" => "Single Entry Ticket",
                "begin" => "2010-03-16",
                "end" => "2010-03-16",  
                "price" => .05,
            ],
            [   "type" => "Single Entry Ticket",
                "begin" => "2010-03-17",
                "end" => "2010-03-17",  
                "price" => .05,
            ],
        ];

        $total = 0;

        // todo: collision check
        foreach ($bookings as $booking) {
            $row = $this -> Bookings -> newEntity();
            $row -> coworker_id = $user -> id;
            $row -> payment_id = 1;
            $row -> host_id = $hostid;
            $row -> description = $booking[ "type" ];
            $row -> price = $booking[ "price" ];
            $row -> servicefee_host = 0;
            $row -> servicefee_coworker = 0;
            $row -> vat = ($booking[ "price" ] / 100 * 20);
            $row -> begin = date("Y-m-d", strtotime($booking[ "begin" ]));
            $row -> end = date("Y-m-d", strtotime($booking[ "end" ]));

            if ($this -> Bookings -> save($row)) {
                
                // find host
                foreach ($hosts as $host) {
                    if ($host -> id == $row -> host_id) {
                        break;
                    }
                }

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
        $rets = [ "total" => $total ];

        echo json_encode($rets, JSON_PRETTY_PRINT);
        exit();
    }
}
?>