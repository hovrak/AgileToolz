<?php

namespace toolz;

require './vendor/autoload.php';
require 'db.php';

$app = new App('centered', false, true);

$app->add(['Header', 'Welcome to Auth Add-on demo app']);
$app->add(['Button', 'Run migration wizard', 'icon'=>'gift'])->link(['wizard']);

$app->add(['ui'=>'divider']);
$app->add(['Button', 'Log-in', 'icon'=>'sign in'])->link(['toolz/login']);
$app->add(['Button', 'Register', 'icon'=>'edit'])->link(['toolz/register']);
$app->add(['Button', 'Dashboard', 'icon'=>'dashboard'])->link(['toolz/dashboard']);

$app->add(['ui'=>'divider']);
$app->add(['Button', 'Admin', 'icon'=>'lock open'])->link(['toolz/admin-users']);
