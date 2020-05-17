<?php
namespace toolz;

require '../vendor/autoload.php';
require 'db.php';

$app = new App(false);
$app->add(['defaultTemplate'=>dirname(__DIR__).'/template/all.html'], 'Section');
