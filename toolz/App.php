<?php

namespace toolz;

use toolz\Model\Tools;
use toolz\Model\Users;

/**
 * @package toolz
 */
class App extends \atk4\ui\App
{
    use \atk4\core\ConfigTrait;

    public $db;
    public $auth;
    public $title = 'toolz';

    public function __construct($interface = 'centered', $no_db_connect = false, $no_authenticate = false)
    {
        parent::__construct();

        $config_file = __DIR__ . '/config.php';
        $this->readConfig($config_file, 'php-inline');

        if ($interface == 'admin') {
            $this->initLayout('Admin');
            $this->layout->leftMenu->addItem(['User Admin', 'icon'=>'users'], ['admin-users']);
            $this->layout->leftMenu->addItem(['Role Admin', 'icon'=>'tasks'], ['admin-roles']);
            $this->layout->leftMenu->addItem(['Back to Index', 'icon'=>'arrow left'], ['../index']);
        } elseif ($interface == 'centered') {
            $this->initLayout('Centered');
        } else {
            $this->initLayout(new \atk4\login\Layout\Narrow());
        }


        if (!$no_db_connect) {
            $this->dbConnect($this->config['dsn']);
        }

        if (!$no_authenticate) {
            $this->authenticate();
        }
    }

    public function authenticate()
    {
        $this->auth = $this->add(new \atk4\login\Auth(['check'=>true]));

        $m = new \toolz\Model\Users($this->db);

        $this->auth->setModel($m);

        $this->auth->setACL(new \atk4\login\ACL(), $this->db);
    }
}
