<?php
namespace App\Model\Table;
use Cake\ORM\Table;

class PicturesTable extends Table {
    public function initialize(array $config) {
        $this->belongsTo('Hosts');
    }
}

?>