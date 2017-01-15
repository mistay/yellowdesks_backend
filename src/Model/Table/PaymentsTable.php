<?php
namespace App\Model\Table;
use Cake\ORM\Table;

class PaymentsTable extends Table {
    public function initialize(array $config) {
        $this->belongsTo('Bankaccounts');
        $this->hasMany('Bookings');
    }
}

?>