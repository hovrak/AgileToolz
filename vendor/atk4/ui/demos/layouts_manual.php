<?php
/**
 * Demonstrates how to use layouts.
 */
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/somedatadef.php';
date_default_timezone_set('UTC');

$layout = new \atk4\ui\Layout\Generic(['defaultTemplate' => './templates/layout1.html']);

try {
    \atk4\ui\Lister::addTo($layout, [], ['Report'])
        ->setModel(new Somedata());

    echo $layout->render();
} catch (\atk4\core\Exception $e) {
    var_dump($e->getMessage());
    var_dump($e->getParams());

    throw $e;
}
