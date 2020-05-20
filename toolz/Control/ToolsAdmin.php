<?php
namespace toolz\Control;

use atk4\core\DebugTrait;
use atk4\core\Exception;
use atk4\data\Model;
use atk4\ui\CRUD;
use atk4\ui\Exception\NoRenderTree;
use atk4\ui\View;

/**
 * View for Tools administration.
 * Includes Tool association with status, required.
 */
class ToolsAdmin extends View
{
    use DebugTrait;

    /** @var crud */
    public $crud = null;

    /**
     * Initialization.
     */
    public function init()
    {
        parent::init();
        $this->crud = $this->add('CRUD');
    }

    /**
     * Initialize User Admin and add all the UI pieces.
     *
     * @param Model $tools
     *
     * @throws \atk4\core\Exception
     * @throws \atk4\ui\Exception
     * @throws \atk4\ui\Exception\NoRenderTree
     *
     * @return Model
     */
    public function setModel(Model $tools)
    {
        
        //$tools->getAction('add')->system = true;

        // set model for CRUD
        $this->crud->setModel($tools);

        // Add new table column used for actions
        //$a = $this->crud->table->addColumn(null, ['ActionButtons', 'caption'=>'']);

        $this->crud->addQuickSearch(['description'], true);
        $this->crud->ipp = 5;
        
        $this->crud->addDecorator("description", ["Text"]);

        $table = $this->crud->table->addHook('beforeRow',function($table){
            if ($table->current_row['status_id']=='1'){
                $table->t_row->template[0] = "\n  <tr class=\"green\" data-id=\"";
        
            }elseif ($table->current_row['status']=='2'){
                $table->t_row->template[0] =  "\n  <tr class=\"red\" data-id=\"";
            }else{
                $table->t_row->template[0] = "\n  <tr data-id=\"";
            }
        });

        return parent::setModel($tools);
    }
}