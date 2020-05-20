<?php
namespace toolz\Model;

use atk4\data\Model;
//use atk4\data\ValidationException;

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
        $this->addField('number');

        $this->hasOne('status', [Status::class, 'our_field'=>'status_id', 'their_field'=>'id', 'caption'=>'status'])->addTitle();

        
/* , ['Multiformat', function($m) {

            if ($m->get('status_id') > 1) {
                return ['Money', ['Class', 'badge badge-success']];
            } elseif (abs($m->get('is_refunded')) < 50) {
                return [['Template', 'Amount was <b>refunded</b>']];
            }
        
            return 'Money';
        }] */

        //$this->addField('');
        $this->addField('required_id', [
            'type' => 'string',
            'ui' => [
                'class' => [
                    'badge',
                ],
            ],
        ]);

        //$this->addField('Received');
        //$this->addField('required');
        //$this->addField('in_inventory');
        $this->addField('location');
        //$this->addField('Location_notes');
        $this->addField('svc_grp');
        $this->addField('ct');
        $this->addField('description');
        //$this->addField('year');
        //$this->addField('note');
        //$this->addField('comment');

        
        // currently user can have only one role. In future it should be n:n relation
        //$this->hasOne('Required', [Required::class, 'our_field'=>'required_id', 'their_field'=>'id', 'caption'=>'required'])->withTitle();

        // traits
        //$this->setupUserModel();
    }
}
