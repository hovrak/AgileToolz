<?php
/**
 * Testing form.
 */
require_once __DIR__ . '/init.php';
require_once __DIR__ . '/database.php';

// create header
\atk4\ui\Header::addTo($app, ['Database-driven form with an enjoyable layout']);

// create form
$form = \atk4\ui\Form::addTo($app, ['segment']);
//$form = \atk4\ui\Form::addTo($app, ['segment', 'buttonSave'=>false]);
//$form = \atk4\ui\Form::addTo($app, ['segment', 'buttonSave'=>new \atk4\ui\Button(['Import', 'secondary', 'iconRight'=>'list'])]);
//$form = \atk4\ui\Form::addTo($app, ['segment', 'buttonSave'=>[null, 'Import', 'secondary', 'iconRight'=>'list']]);
\atk4\ui\Label::addTo($form, ['Input new country information here', 'top attached'], ['AboveFields']);

$form->setModel(new Country($db), false);

// form basic field group
$f_address = $form->addGroup('Basic Country Information');
$f_address->addField('name', ['width' => 'sixteen'])
    ->addAction(['Check Duplicate', 'iconRight' => 'search'])
    ->on('click', function ($val) {
        // We can't get the value until https://github.com/atk4/ui/issues/77
        return 'Value appears to be unique';
    });

// form codes field group
$f_codes = $form->addGroup(['Codes']);
$f_codes->addField('iso', ['width' => 'four'])->iconLeft = 'flag';
$f_codes->addField('iso3', ['width' => 'four'])->iconLeft = 'flag';
$f_codes->addField('numcode', ['width' => 'four'])->iconLeft = 'flag';
$f_codes->addField('phonecode', ['width' => 'four'])->iconLeft = 'flag';

// form names field group
$f_names = $form->addGroup(['More Information about you']);
$f_names->addField('first_name', ['width' => 'eight']);
$f_names->addField('middle_name', ['width' => 'three']);
$f_names->addField('last_name', ['width' => 'five']);

// form on submit
$form->onSubmit(function ($f) {

    // In-form validation
    $errors = [];
    if (strlen($f->model['first_name']) < 3) {
        $errors[] = $f->error('first_name', 'too short, ' . $f->model['first_name']);
    }
    if (strlen($f->model['last_name']) < 5) {
        $errors[] = $f->error('last_name', 'too short');
    }
    if (isset($f->model->dirty['iso'])) { // restrict to change iso field value
        $errors[] = $f->error('iso', 'Field value should not be changed');
    }

    if ($errors) {
        return $errors;
    }

    // Model will have some validation too
    $f->model->save();

    return $f->success(
        'Record Added',
        'there are now ' . $f->model->action('count')->getOne() . ' records in DB'
    );
});

// ============================================================================

class Person extends \atk4\data\Model
{
    public $table = 'person';

    public function init()
    {
        parent::init();
        $this->addField('name', ['required' => true]);
        $this->addField('surname', ['ui'=>['placeholder'=>'e.g. Smith']]);
        $this->addField('gender', ['enum' => ['M', 'F']]);
        $this->hasOne('country_lookup_id', new Country()); // this works fast
        $this->hasOne('country_dropdown_id', [new Country(), 'ui'=>['form'=>new \atk4\ui\FormField\DropDown()]]); // this works slow
    }

    public function validate($intent = null)
    {
        $errors = parent::validate();

        if ($this['name'] == $this['surname']) {
            $errors['surname'] = 'Your surname cannot be same as the name';
        }

        return $errors;
    }
}

\atk4\ui\Form::addTo($app)
  ->addClass('segment')
    ->setModel(new Person($db));
