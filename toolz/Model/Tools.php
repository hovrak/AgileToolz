<?php
namespace toolz\Model;

use atk4\data\Model;
use atk4\data\ValidationException;

# Features of tools model
use atk4\login\Feature\SetupModel;
use atk4\login\Feature\UniqueFieldValue;

/**
 * tools data model.
 */
class Tools extends Model
{
    use SetupModel;
    use UniqueFieldValue;

    public $table = 'mbtools';
    public $caption = 'tools';

    public function init()
    {
        parent::init();

        $this->addField('id');
        $this->addField('number');
        $this->addField('rstatus');
        $this->addField('in_inventory');
        $this->addField('location');
        $this->addField('Location_notes');
        $this->addField('svc_grp');
        $this->addField('ct');
        $this->addField('description');
        $this->addField('year');
        $this->addField('note');
        $this->addField('comment');
        $this->addField('');

        // currently user can have only one role. In future it should be n:n relation
        $this->hasOne('status_id', [Status::class, 'our_field'=>'status_id', 'their_field'=>'id', 'caption'=>'Status'])->withTitle();
        //$this->hasOne('required_id', [Required::class, 'our_field'=>'required_id', 'their_field'=>'id', 'caption'=>'Required'])->withTitle();

        // traits
        //$this->setupUserModel();
    }
}
