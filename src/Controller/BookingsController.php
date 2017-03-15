<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\Event\Event;

class BookingsController extends AppController {
    
    public function index() {
        if (!$this -> hasAccess([Roles::ADMIN])) return $this->redirect(["controller" => "users", "action" => "login", "redirect_url" =>  $_SERVER["REQUEST_URI"]]); 

        $this->cleanupBookings();
        
        $model = TableRegistry::get('Bookings');

        $user = $this -> getLoggedinUser();

        $query = $model->find('all')->order(["dt_inserted DESC"])->where()->contain(['Hosts', 'Coworkers']);
        $this->set("rows", $query);
    }

    public function mybookings() {
        if (!$this -> hasAccess([Roles::COWORKER])) return $this->redirect(["controller" => "users", "action" => "login", "redirect_url" =>  $_SERVER["REQUEST_URI"]]); 

        $this->cleanupBookings();
        
        $model = TableRegistry::get('Bookings');

        $user = $this -> getLoggedinUser();

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
    
    public function invoicehost() {
        if (!$this -> hasAccess([Roles::HOST])) return $this->redirect(["controller" => "users", "action" => "login", "redirect_url" =>  $_SERVER["REQUEST_URI"]]); 
 
        $user = $this -> getloggedinUser();

        $model = TableRegistry::get('Bookings');
        $query = $model->find('all')->contain(['Coworkers'])->where(['paypalipn_id IS NOT' => null, 'host_id' => $user->id]);

        $hosts = TableRegistry::get('Hosts');
        $this->set("host", $hosts->get( $user->id));

        // todo: security: check if user is permitted to request this invoice
        $this->set("rows", $query);
    }
    
    public function preparebookingrequest() {
        if (!$this -> hasAccess([Roles::COWORKER])) return $this->redirect(["controller" => "users", "action" => "login", "redirect_url" =>  $_SERVER["REQUEST_URI"]]); 
    }
    

    /*  see: AGBs 
        $str_date1 $str_date2, e.g. '2017-02-01'
    */
    private function calculate_timespan($str_date_begin, $str_date_end) {
        $date1 = new \DateTime($str_date_begin);
        $date2 = new \DateTime($str_date_end); 
        $date2->add(new \DateInterval('P1D')); // end date is included as of definition

        $diff = $date1->diff($date2);
        //printf('%u year(s), %u month(s), %u day(s)', $diff->y, $diff->m, $diff->d);

        return ["years" => $diff->y, "months" => $diff->m, "days" => $diff->d];
    }

    /*  see: AGBs 
        $str_date1 $str_date2, e.g. '2017-02-01'
    */
    private function calculate_workingdays($host_id, $str_date_begin, $str_date_end) {

        $from = strtotime($str_date_begin);
        $to = strtotime($str_date_end);

        $hosts = TableRegistry::get('Hosts');
        $host = $hosts->get($host_id);

        $modelholidays = TableRegistry::get('Holidays');
        $holidays = $modelholidays->find('all'); // todo: auf land/zeitraum? einschränken?

        $days = 0;
        $workingdays = [];
        do {
            $test_date = mktime(date("H", $from), date("i", $from), date("s", $from), date("m", $from), date("d", $from) + $days, date("Y", $from));
            $days++;

            // 1. continue with next day if day is public holiday
            $found=false;
            foreach ($holidays as $holiday) {
                if (date("Y-m-d", $test_date) == date("Y-m-d", strtotime($holiday->date))) {
                    $found = true;
                    break;
                }
            }
            if ($found)
                // zu testendender tag ist ein feiertag, kein coworking möglich
                continue;

            // 2. add day to list of workingdays if host is open at that day, else continue with next day
            switch (date('N', $test_date)) {
                case 1: //monday
                    if ($host->open_monday_from != null && $host->open_monday_till != null) {
                        array_push($workingdays, date("Y-m-d", $test_date)); continue; }
                    break;
                case 2:
                    if ($host->open_tuesday_from != null && $host->open_tuesday_till != null) {
                        array_push($workingdays, date("Y-m-d", $test_date)); continue; }
                    break;
                case 3:
                    if ($host->open_wednesday_from != null && $host->open_wednesday_till != null) {
                        array_push($workingdays, date("Y-m-d", $test_date)); continue; }
                    break;
                case 4:
                    if ($host->open_thursday_from != null && $host->open_thursday_till != null) {
                        array_push($workingdays, date("Y-m-d", $test_date)); continue; }
                    break;
                case 5:
                    if ($host->open_friday_from != null && $host->open_friday_till != null) {
                        array_push($workingdays, date("Y-m-d", $test_date)); continue; }
                    break;
                case 6:
                    if ($host->open_saturday_from != null && $host->open_saturday_till != null) {
                        array_push($workingdays, date("Y-m-d", $test_date)); continue; }
                    break;
                case 7:
                    if ($host->open_sunday_from != null && $host->open_sunday_till != null) {
                        array_push($workingdays, date("Y-m-d", $test_date)); continue; }
                    break;
            }

            // we assume host is open mo-fr if no opening hours set
            if ($host->open_monday_from == null && $host->open_monday_till == null &&
                $host->open_tuesday_from == null && $host->open_tuesday_till == null &&
                $host->open_wednesday_from == null && $host->open_wednesday_till == null &&
                $host->open_thursday_from == null && $host->open_thursday_till == null &&
                $host->open_friday_from == null && $host->open_friday_till == null &&
                $host->open_saturday_from == null && $host->open_saturday_till == null &&
                $host->open_sunday_from == null && $host->open_sunday_till == null)

                if (date('N', $test_date) >= 1 && date('N', $test_date) <= 5) {
                    // mo - fr
                    array_push($workingdays, date("Y-m-d", $test_date)); continue;
                }
        } while($test_date < $to);
        return $workingdays;
    }

/*
Auszug aus YD-AGBs:

preisberechnung
===============
die reihenfolge der untenstehenden regeln ist relevant. sobald eine regelbedingung erfüllt ist, wird nur diese regel (und keine andere) angewendet.

1. regel: bei buchungen > 1monat: monatspreis für anzahl der monate, letzter monat aliquot.
z.B: coworker bucht von 1.2. bis 7.3. (=1,22 monate da 1 februarmonat + 7 tage im märz), monatspreis: 309EUR
     kosten für coworker: 1,22 Monate * 309EUR = 376,98 EUR

2. regel: bei buchungen > 10 tage: kosten für coworker: 10-tagespreis * anzahl werktage / 10
z.B: 13 Tage (10er-Tagespreis: 215 EUR) kosten 13 Tage * 215EUR / 10 = 259,50EUR

3. regel: bei buchungen > 1 tag: kosten einzelticketpreis * anzahl der tage
z.B: 3 Tage ju je 25EUR kosten 3 * 25EUR = 75EUR

day of rest ("ruhetag")
=======================
definition
  1. all public holidays in austria, listed in wikipedia (https://en.wikipedia.org/wiki/List_of_holidays_by_country)
  2. all days in hosts's profile where no opening-hours are set (at the time of booking)

example: coworker books from 31.10.2017 to 7.11.2017 at host "coworkingsalzburg" (assuming opening hours on monday till saturday)
   tue 31.10.2017 = working day ("arbeitstag"), will be charged
   wed 1.11.2017 = public holiday as of wikipedia, day of rest, no charge
   thu 2.11.2017 = working day ("arbeitstag"), will be charged
   fri 3.11.2017 = working day ("arbeitstag"), will be charged
   sat 4.11.2017 = working day ("arbeitstag"), will be charged
   sun 5.11.2017 = day of rest, no charge
   mon 6.11.2017 = working day ("arbeitstag"), will be charged
   tue 7.11.2017 = working day ("arbeitstag"), will be charged
*/
    public function prepare($hostid, $begin, $end, $requestOffer=false) {
        if ($requestOffer !== false) {
            // login if user would like to book, otherwise it's just a price calculation (no login needed)
            if (!$this -> hasAccess([Roles::COWORKER])) return $this->redirect(["controller" => "users", "action" => "login", "redirect_url" =>  $_SERVER["REQUEST_URI"]]); 
        }
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

            $to = $from;
        }

        // todo: 2h-ticket (wie?)
        $total = 0;
        $timespan = $this->calculate_timespan($begin, $end);
        $rets["num_months"] = $timespan["months"];
        $rets["num_days"] = $timespan["days"];

        if ($timespan["months"] >= 6) {
            // 1st rule
            $rets["debug_rule"] = 1;
            $total = ($timespan["months"] + ($timespan["days"] / 30)) * $host -> price_6months / 6;
        } elseif ($timespan["months"] >= 1) {
            // 2nd rule
            $rets["debug_rule"] = 2;
            $total = ($timespan["months"] + ($timespan["days"] / 30)) * $host -> price_1month;
        } else {
            $workingdays = $this -> calculate_workingdays($hostid, $begin, $end);
            $rets["workingdays"] = $workingdays;
            $rets["num_workingdays"] = sizeof($workingdays);

            if (sizeof($workingdays) >= 10)  {
                // 3rd rule
                $rets["debug_rule"] = 3;
                $total = sizeof($workingdays) * $host -> price_10days / 10;
            } elseif (sizeof($workingdays) >= 1)  {
                // 4th rule
                $rets["debug_rule"] = 4;
                $total = sizeof($workingdays) * $host -> price_1day;
            }
        }
        
        $booking = [
            "type" => "Yellowdesk Ticket",
            "begin" => date("Y-m-d", $from),
            "end" => date("Y-m-d", $to),  
            "price" => $total,
        ];

        // todo: collision check

        $total_bookings = 0;
        
        $row = $this -> Bookings -> newEntity();
        if ($requestOffer !== false)
            $row -> coworker_id = $user -> id;
        $row -> payment_id = null;
        $row -> host_id = $hostid;
        $row -> description = $booking[ "type" ];
        $row -> price = $booking[ "price" ];
        $row -> servicefee_host = $booking[ "price" ] / 100 * 20; // 20% to YD

        // https://de.wikipedia.org/wiki/Rundung
        // kaufmaennisch gerundet: stelle die wegfaellt 0,1,2,3 od 4: abrunden, sonst: aufrunden.
        $row -> vat = round(($booking[ "price" ] / 100 * 20), 2, PHP_ROUND_HALF_UP);
        $row -> amount_host = $row -> price - $row -> servicefee_host;
        $row -> vat_host = round(($row -> amount_host / 100 * 20), 2, PHP_ROUND_HALF_UP); // todo: gilt nur für .at unternehmer

        $row -> begin = date("Y-m-d", strtotime($booking[ "begin" ]));
        $row -> end = date("Y-m-d", strtotime($booking[ "end" ]));

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
        
        if ($requestOffer !== false) {
            $this -> Bookings -> save($row);
            $rets[$row->id] = $ret;
        }
        
        $total_bookings += $row -> price + $row -> vat;
        
        $rets["total"] = $total_bookings;

        if (@$_REQUEST["jsonbrowser"]) echo "<pre>";
        echo json_encode($rets, JSON_PRETTY_PRINT);
        exit();
    }
}
?>