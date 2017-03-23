<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;

use Cake\Http\Response;

use Cake\ORM\TableRegistry;
use Cake\Mailer\Email;


/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends CrumbsController
{

    var $appconfigs = [];
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        
        $this->response = $this->response->withHeader('X-API-Level', '1');
        
        $model = TableRegistry::get('Configs');
        $query = $model->find('all');
        foreach ($query as $tmp) {
            $this -> appconfigs [$tmp["configkey"]] = $tmp["configvalue"];
        }

        Email::setConfigTransport('appdefault', [
            'host' => $this -> appconfigs['emailhost'],
            'port' => 25,
            'username' => $this -> appconfigs['emailusername'],
            'password' => $this -> appconfigs['emailpassword'],
            'className' => 'Smtp',
            'tls' => true
        ]);

        //y//
        /*
        $this->loadComponent('Auth', [
            'loginRedirect' => [
                'controller' => 'Hosts',
                'action' => 'index'
            ],
            'logoutRedirect' => [
                'controller' => 'Pages',
                'action' => 'display',
                'home'
            ]
        ]);
        */
        //y//

        /*
         * Enable the following components for recommended CakePHP security settings.
         * see http://book.cakephp.org/3.0/en/controllers/components/security.html
         */
        //$this->loadComponent('Security');
        //$this->loadComponent('Csrf');
    }

    /**
     * Before render callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return \Cake\Network\Response|null|void
     */
    public function beforeRender(Event $event)
    {
        
        if (!array_key_exists('_serialize', $this->viewVars) &&
            in_array($this->response->type(), ['application/json', 'application/xml'])
        ) {
            $this->set('_serialize', true);
        }
        
    }

    public function timegmt() {
        return time() - (int)substr(date('O'),0,3)*60*60;
    }

    public function cleanupbookings() {
        // delete all reserverations that are 15 days old
        // zu heiß. es können jetzt auch die hosts bis zu tage später buchungen akzeptieren
        return;

        // todo: ueberlegen wie wir cleanup loesen koennen
        $model = TableRegistry::get('Bookings');
        $time = date("Y-m-d H:i:s",  $this->timegmt() - (15 * 60));
        $query = $model->deleteAll(["dt_inserted < " => $time]);
    }
}
