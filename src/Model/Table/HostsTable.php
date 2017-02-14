<?php
namespace App\Model\Table;
use Cake\ORM\Table;

class HostsTable extends Table {
    public function initialize(array $config) {
        $this->hasMany('Pictures');
        $this->hasMany('Payments');
        $this->hasMany('Videos');
    }
}

?>