<?php

require_once __DIR__ . '/init.php';
require_once __DIR__ . '/database.php';

\atk4\ui\Button::addTo($app, ['Accordion in Form', 'small right floated basic blue', 'iconRight' => 'right arrow'])
    ->link(['form-section-accordion']);
\atk4\ui\View::addTo($app, ['ui' => 'ui clearing divider']);

$m = new Country($db);
$m->loadAny();

//Prevent form from saving
$noSave = function ($f) {
    return new \atk4\ui\jsToast([
        'title'       => 'POSTed field values',
        'message'     => '<pre>' . json_encode($f->model->get(), JSON_PRETTY_PRINT) . '</pre>',
        'class'       => 'success',
        'displayTime' => 5000,
    ]);
};

////////////////////////////////

$f = \atk4\ui\Form::addTo($app);
$f->setModel($m, false);

$sub_layout = $f->layout->addSubLayout('Generic');

\atk4\ui\Header::addTo($sub_layout, ['Column Section in Form']);
$sub_layout->setModel($m, ['name']);

$cols_layout = $f->layout->addSubLayout('Columns');

$c1 = $cols_layout->addColumn();
$c1->setModel($m, ['iso', 'iso3']);

$c2 = $cols_layout->addColumn();
$c2->setModel($m, ['numcode'/*, 'phonecode'*/]);

$f->addField('phonecode');

$f->onSubmit($noSave);

\atk4\ui\View::addTo($app, ['ui' => 'divider']);

////////////////////////////////

$f = \atk4\ui\Form::addTo($app);
$f->setModel($m, false);

$sub_layout = $f->layout->addSubLayout('Generic');

\atk4\ui\Header::addTo($sub_layout, ['Accordion Section in Form']);
$sub_layout->setModel($m, ['name']);

$accordion_layout = $f->layout->addSubLayout('Accordion');

$a1 = $accordion_layout->addSection('Section 1');
$a1->setModel($m, ['iso', 'iso3']);

$a2 = $accordion_layout->addSection('Section 2');
$a2->setModel($m, ['numcode', 'phonecode']);

$f->onSubmit($noSave);

\atk4\ui\View::addTo($app, ['ui' => 'divider']);

////////////////////////////////

$f = \atk4\ui\Form::addTo($app);
$f->setModel($m, false);

$sub_layout = $f->layout->addSubLayout('Generic');

\atk4\ui\Header::addTo($sub_layout, ['Tabs in Form']);
$sub_layout->setModel($m, ['name']);

$tabs_layout = $f->layout->addSubLayout('Tabs');

$t1 = $tabs_layout->addTab('Tab 1');
$t1->addGroup('In Group')->setModel($m, ['iso', 'iso3']);

$t2 = $tabs_layout->addTab('Tab 2');
$t2->setModel($m, ['numcode', 'phonecode']);

$f->onSubmit($noSave);

\atk4\ui\View::addTo($app, ['ui' => 'divider']);

/////////////////////////////////////////

\atk4\ui\Header::addTo($app, ['Color in form']);

$f = \atk4\ui\Form::addTo($app);
$f->setModel($m, false);

$sub_layout = $f->layout->addSubLayout(['Generic', 'ui' => 'segment red inverted'], false);

\atk4\ui\Header::addTo($sub_layout, ['This section in Red', 'ui' => 'dividing header', 'element' => 'h2']);
$sub_layout->setModel($m, ['name']);

$sub_layout = $f->layout->addSubLayout(['Generic', 'ui' => 'segment teal inverted']);
$cols_layout = $sub_layout->addSubLayout('Columns');

$c1 = $cols_layout->addColumn();
$c1->setModel($m, ['iso', 'iso3']);

$c2 = $cols_layout->addColumn();
$c2->setModel($m, ['numcode', 'phonecode']);

$f->onSubmit($noSave);

\atk4\ui\View::addTo($app, ['ui' => 'divider']);
