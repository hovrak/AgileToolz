<?php
namespace toolz\Model;

use atk4\data\Model;

//use atk4\login\Feature\SetupModel;
//use atk4\login\Feature\UniqueFieldValue;

class Status extends Model
{
    //use SetupModel;
    //use UniqueFieldValue;

    public $table = 'status';
    public $caption = 'Status';

    public function init()
    {
        parent::init();

        $this->addField('name');

        $this->hasMany('Tools', [Tools::class, 'our_field'=>'id', 'their_field'=>'status_id']);
        //$this->hasMany('AccessRules', [AccessRule::class, 'our_field'=>'id', 'their_field'=>'role_id']);

        // traits
        //$this->setupRoleModel();
    }
}
