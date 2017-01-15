<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;

class PaymentsController extends AppController {
    
    public function index() {
        $model = TableRegistry::get('Payments');
        $query = $model->find('all')->contain(['Bankaccounts', 'Bookings.Coworkers']);
        $this->set("rows", $query);
    }
}
?>