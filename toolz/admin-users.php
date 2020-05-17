<?php
namespace toolz;

require '../vendor/autoload.php';
require '../db.php';

use atk4\login\UserAdmin;
use toolz\Model\Users;

$app = new App('admin');

// USERS --------------------------------------------------
$app->add('Header')->set('Users');
$app->add(new UserAdmin())
    ->setModel(new Users($app->db));
