<?php
namespace toolz\Model;

use atk4\data\Model;

//use atk4\login\Feature\SetupModel;
//use atk4\login\Feature\UniqueFieldValue;

class Required extends Model
{
    //use SetupModel;
    //use UniqueFieldValue;

    public $table = 'ro';
    public $caption = 'Required';

    public function init()
    {
        parent::init();

        $this->addField('rstatus');

        $this->hasMany('Tools', [Tools::class, 'our_field'=>'id', 'their_field'=>'required_id']);
        //$this->hasMany('AccessRules', [AccessRule::class, 'our_field'=>'id', 'their_field'=>'role_id']);

        // traits
        //$this->setupRoleModel();
    }
}
