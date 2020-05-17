<?php

require_once __DIR__ . '/init.php';
require_once __DIR__ . '/database.php';

//For popup positioning to work correctly, table need to be inside a view segment.
$view = \atk4\ui\View::addTo($app, ['ui' => 'basic segment']);
$g = \atk4\ui\Grid::addTo($view);

$m = new Country($db);
$m->addExpression('is_uk', 'if([iso] = "GB", 1, 0)')->type = 'boolean';

$g->setModel($m);
$g->addFilterColumn();

$g->ipp = 20;
