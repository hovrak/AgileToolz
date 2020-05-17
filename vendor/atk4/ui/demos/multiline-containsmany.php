<?php

require_once __DIR__ . '/init.php';
require_once __DIR__ . '/database.php';

class Client extends atk4\data\Model
{
    public $table = 'client';
    public $caption = 'Client';

    public function init()
    {
        parent::init();

        $data = [];

        $this->addField('name');
        $this->containsMany('Accounts', [Account::class]);
    }
}

class Account extends atk4\data\Model
{
    public $caption = ' ';

    public function init()
    {
        parent::init();

        $this->addField('email', ['required' => true, 'ui' => ['multiline' => ['input', ['icon' => 'envelope', 'type' => 'email']]]]);
        $this->addField('password', ['required' => true, 'ui' => ['multiline' => ['input', ['icon' => 'key', 'type' => 'password']]]]);
        $this->addField('site', ['required' => true]);
        $this->addField('type', ['default' => 'user', 'values' => ['user' => 'Regular User', 'admin' => 'System Admin'], 'ui' => ['multiline' => ['width' => 'four']]]);
    }
}

\atk4\ui\CRUD::addTo($app)->setModel(new Client($db));
