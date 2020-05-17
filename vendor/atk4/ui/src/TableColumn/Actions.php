<?php

namespace atk4\ui\TableColumn;

use atk4\core\FactoryTrait;
use atk4\ui\Button;

/**
 * Formatting action buttons column.
 */
class Actions extends Generic
{
    use FactoryTrait;

    public $actions = [];

    public function init()
    {
        parent::init();
        $this->addClass('right aligned');
    }

    /**
     * Adds a new button which will execute $callback when clicked.
     *
     * Returns button object
     *
     * @param $button
     * @param $callback
     * @param bool $confirm
     * @param bool $isDisabled
     *
     * @throws \atk4\core\Exception
     * @throws \atk4\data\Exception
     *
     * @return object
     */
    public function addAction($button, $callback = null, $confirm = false, $isDisabled = false)
    {
        // If action is not specified, perhaps it is defined in the model
        if (!$callback && is_string($button)) {
            $model_action = $this->table->model->getAction($button);
            if ($model_action) {
                $isDisabled = !$model_action->enabled;
                $callback = $model_action;
                $button = $callback->caption;
                if ($model_action->ui['confirm'] ?? null) {
                    $confirm = $model_action->ui['confirm'];
                }
            }
        } elseif (!$callback && $button instanceof \atk4\data\UserAction\Generic) {
            $isDisabled = !$button->enabled;
            if ($button->ui['confirm'] ?? null) {
                $confirm = $button->ui['confirm'];
            }
            $callback = $button;
            $button = $button->caption;
        }

        $name = $this->name.'_action_'.(count($this->actions) + 1);

        if ($callback instanceof \atk4\data\UserAction\Generic) {
            if (isset($callback->ui['button'])) {
                $button = $callback->ui['button'];
            }

            if (isset($callback->ui['confirm'])) {
                $confirm = $callback->ui['confirm'];
            }
        }

        if (!is_object($button)) {
            $button = $this->factory('Button', [$button, 'id' => false], 'atk4\ui');
        }

        if ($button->icon && !is_object($button->icon)) {
            $button->icon = $this->factory('Icon', [$button->icon, 'id' => false], 'atk4\ui');
        }

        $button->app = $this->table->app;

        $this->actions[$name] = $button;
        $button->addClass('b_'.$name);
        $button->addClass('compact');
        if ($isDisabled) {
            $button->addClass('disabled');
        }
        $this->table->on('click', '.b_'.$name, $callback, [$this->table->jsRow()->data('id'), 'confirm' => $confirm]);

        return $button;
    }

    /**
     * Adds a new button which will open a modal dialog and dynamically
     * load contents through $callback. Will pass a virtual page.
     */
    public function addModal($button, $title, $callback, $owner = null, $args = [])
    {
        if (!$owner) {
            $modal = $this->owner->owner->add(['Modal', 'title'=>$title]);
        } else {
            $modal = $owner->add(['Modal', 'title'=>$title]);
        }
        $modal->observeChanges(); // adds scrollbar if needed

        $modal->set(function ($t) use ($callback) {
            call_user_func($callback, $t, $this->app->stickyGet($this->name));
        });

        return $this->addAction($button, $modal->show(array_merge([$this->name=>$this->owner->jsRow()->data('id')], $args)));
    }

    /**
     * {@inheritdoc}
     */
    public function getTag($position, $value, $attr = [])
    {
        if ($this->table->hasCollapsingCssActionColumn && $position === 'body') {
            $attr['class'][] = 'collapsing';
        }

        return parent::getTag($position, $value, $attr);
    }

    public function getDataCellTemplate(\atk4\data\Field $f = null)
    {
        if (!$this->actions) {
            return '';
        }

        // render our actions
        $output = '';
        foreach ($this->actions as $action) {
            $output .= $action->getHTML();
        }

        return '<div class="ui buttons">'.$output.'</div>';
    }

    // rest will be implemented for crud
}
