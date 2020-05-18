<?php
namespace toolz;

include '../vendor/autoload.php';
include '../db.php';

use toolz\Control\ToolsFront;
use toolz\Model\Tools;

$app = new App('centered', false, true);

// USERS --------------------------------------------------
$app->add('header')->set('Start Typing in Search to Quickly Find Tools');
$app->add(new ToolsFront())
    ->setModel(new Tools($app->db));
