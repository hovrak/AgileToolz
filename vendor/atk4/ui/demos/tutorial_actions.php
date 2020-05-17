<?php

require 'init.php';
require 'database.php';

/** @var \atk4\ui\View $wizard */
$wizard = $app->add('Wizard');
$app->stickyGet($wizard->name);

$wizard->addStep('Actions in Agile Data', function ($page) {
    /** @var \atk4\ui\Text $t */
    $t = $page->add('Text');
    $t->addParagraph(<<< 'EOF'
Models of Agile Data has always supported 3 basic actions: "save" (for new and existing records) and "delete". In
version 1.4 of Agile Data "User Actions" were included.
EOF
    );

    $page->add(new Demo())->setCode(<<<'CODE'
$country = new Country($app->app->db);

$country->addAction('send_message');

$app->add(['element'=>'pre'])
    // todo: add dumping!
    ->set(get_class($country->getAction('send_message')));
CODE
    );

    $t = $page->add('Text');
    $t->addParagraph(<<< 'EOF'
Just like "addField" describes a model field that user can see and interact through Table, Grid, Form or CRUD, method
"addAction" describes an action that user can trigger through Grid, Card, CRUD and Button.
EOF
    );

    $t->addParagraph(<<< 'EOF'
Each action can have caption, be declared system and have many other properties that can be recognized by generic UI
and API adapters.
EOF
    );

    $t->addParagraph(<<< 'EOF'
Finally Agile Data 2.0 declares three actions for each model: "add", "edit" and "delete" just to keep everything
consistent. And actions are really flexible - you can remove them or add them.
EOF
    );

    $page->add(new Demo())->setCode(<<<'CODE'
$model = new atk4\data\Model($app->db, 'test');
// $model->removeAction('delete');
$model->getAction('delete')->enabled = false;
$model->addAction('soft_delete', [
    'scope' => \atk4\data\UserAction\Generic::SINGLE_RECORD,
    'ui'    => [
        'icon'=>'trash', 
        'button'=>[null, 'icon'=>'red trash'], 
        'confirm'=>'Are you sure?'
    ],
    'callback' => function ($m) {
        $m['deleted'] = true;
        $m->saveAndUnload();
    },
]);
$app->add(['element'=>'pre'])
    ->set(json_encode(array_keys($model->getActions())));
CODE
    );
});

$wizard->addStep('UI for Actions', function ($page) {
    /** @var \atk4\ui\Text $t */
    $t = $page->add('Text');
    $t->addParagraph(<<< 'EOF'
With all that meta-information the Agile UI framework can now fully integrate actions everywhere! Traditionally,
let us start with a button.
EOF
    );

    $page->add(new Demo())->setCode(<<<'CODE'
$country = new Country($app->app->db);
$country->loadAny();

$app->add(['Button', 'Edit some country'])
    ->on('click', $country->getAction('edit'));
CODE
    );

    $t = $page->add('Text');
    $t->addParagraph(<<< 'EOF'
Any view can actually pass action as a callback, not only the button. Here is another demo:
EOF
    );

    $page->add(new Demo())->setCode(<<<'CODE'
$country = new Country($app->app->db);
$country->loadAny();

$menu = $app->add('Menu');
$menu->addItem('Hello');
$menu->addItem('World', $country->getAction('edit'));
CODE
    );

    $t = $page->add('Text');
    $t->addParagraph(<<< 'EOF'
When action requires an argument, you can either specify it directly or through jsExpression. If you do not do that,
user will be asked to enter the missing arguments.
EOF
    );

    $page->add(new Demo())->setCode(<<<'CODE'
$model = new \atk4\data\Model($app->db, 'test');
$model->addAction('greet', [
    'args'=> [
        'age'=>[
            'type'=>'integer', 
            'required' => true
        ]
    ], 
    'callback'=>function ($m, $age) {
        return 'Age is '.$age;
    }
]);

$app->add(new \atk4\ui\FormField\Line([
    'action' => $model->getAction('greet'),
]));

$app->add(['ui'=>'divider']);

$app->add(['Button', 'Greet without Age argument'])
    ->on('click', $model->getAction('greet'));
CODE
    );
});

$wizard->addStep('More Ways', function ($page) {
    /** @var \atk4\ui\Text $t */
    $t = $page->add('Text');
    $t->addParagraph(<<< 'EOF'
TODO: add example of card deck, table and grid
EOF
    );
});

$wizard->addStep('CRUD integration', function ($page) {
    /** @var \atk4\ui\Text $t */
    $t = $page->add('Text');
    $t->addParagraph(<<< 'EOF'
TODO: add example of crud
EOF
    );

    $page->add(new Demo())->setCode(<<<'CODE'
$country = new Country($app->app->db);
$crud = $app->add(['CRUD', 'ipp'=>5]);
$crud->setModel($country);
CODE
    );
});
