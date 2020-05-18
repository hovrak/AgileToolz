<?php
namespace toolz\Control;

use atk4\core\DebugTrait;
use atk4\core\Exception;
use atk4\data\Model;
use atk4\ui\Grid;
use atk4\ui\Exception\NoRenderTree;
use atk4\ui\View;

/**
 * View for User administration.
 * Includes User association with Role.
 */
class ToolsFront extends View
{
    use DebugTrait;

    /** @var grid */
    public $grid = null;

    /**
     * Initialization.
     */
    public function init()
    {
        parent::init();
        $this->grid = $this->add('Grid');
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
        $this->grid->setModel($tools);

        // Add new table column used for actions
        //$a = $this->crud->table->addColumn(null, ['ActionButtons', 'caption'=>'']);

        $this->grid->addQuickSearch(['description'], true);
        $this->grid->ipp = 5;
        


    return parent::setModel($tools);
    }
}
