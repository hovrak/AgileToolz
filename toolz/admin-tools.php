<?php
namespace toolz;

include '../vendor/autoload.php';
include '../db.php';

use toolz\Control\ToolsAdmin;
use toolz\Model\Tools;

$app = new App('admin');

// USERS --------------------------------------------------
$app->add('header')->set('Start Typing in Search to Quickly Find Tools');
$app->add(new ToolsAdmin())
    ->setModel(new Tools($app->db));
