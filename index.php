<?php

namespace toolz;

require './vendor/autoload.php';
require 'db.php';

$app = new App('centered', false, true);

$app->layout->image = 'toolz\\logo.png';

$app->add(['Header', 'Welcome mb Toolz app']);
$app->add(new \toolz\Control\ToolsFront())
    ->setModel(new \toolz\Model\Tools($app->db));

$app->add(['ui'=>'divider']);
$app->add(['Button', 'Admin', 'icon'=>'lock open'])->link(['toolz/admin-users']);
