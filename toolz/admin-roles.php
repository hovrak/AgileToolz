<?php
namespace toolz;

include '../vendor/autoload.php';
include 'db.php';

use atk4\login\RoleAdmin;
use \toolz\Model\Role;

$app = new App('admin');

// USERS --------------------------------------------------
$app->add('Header')->set('Roles');
$app->add(new RoleAdmin())
    ->setModel(new Role($app->db));
