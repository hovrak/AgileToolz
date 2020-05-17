<?php
/**
 * Javascript Action executor.
 *
 * Will execute a model action using a js Event.
 *
 * Usage:
 * When use with View::on method, then jsUserAction executor is automatically create.
 *  $btn->on('click', $model->getAction('delete') , [4, 'confirm'=> 'This will delete record with id 4. Are you sure?']);
 *
 * Manual setup.
 * $action = $model->getAction('delete')
 * $ex = jsUserAction::addTo($app)->setAction($action, [4])
 * $btn->on('click', $ex, ['confirm'=> 'This will delete record with id 4. Are you sure?']);
 */

namespace atk4\ui\ActionExecutor;

use atk4\core\HookTrait;
use atk4\data\UserAction\Generic;
use atk4\ui\Exception;
use atk4\ui\jsCallback;
use atk4\ui\jsExpressionable;
use atk4\ui\jsToast;
use atk4\ui\View;

class jsUserAction extends jsCallback implements Interface_
{
    use HookTrait;

    /** @var @var Generic The model user action */
    public $action;

    /**
     * @var jsExpressionable array|callable jsExpression to return if action was successful, e.g "new jsToast('Thank you')"
     */
    public $jsSuccess = null;

    /**
     * Set action to be execute.
     *
     * The first single value provide in $urlArgs array will be
     * consider as the model Id to be loaded with the action owner model.
     *
     * Ex.
     *      $btn = \atk4\ui\Button::addTo($app, ['Import File']);
     *      $ex = jsUserAction::addTo($app);
     *      $ex->setAction($f_action, [8, 'path' => '.']);
     *
     *      $btn->on('click', $ex, ['confirm'=> 'This will import a lot of file. Are you sure?']);
     *
     *
     * Note: Id can be set using a single value or a jsExpression, like:
     *      $ex->setAction($f_action, [$field->jsInput()->val(), 'path' => '.']);
     *
     *
     * @param Generic $action
     * @param array   $urlArgs url Argument to pass when callback is trigger.
     *
     * @throws Exception
     *
     * @return $this
     */
    public function setAction(Generic $action, $urlArgs = [])
    {
        if (!$this->_initialized) {
            throw new Exception('Error: Make sure jsUserAction is properly initialized prior to call setAction()');
        }

        $this->action = $action;
        if (!$this->action->enabled && $this->owner instanceof View) {
            $this->owner->addClass('disabled');
        }

        $this->set(function ($j) {
            //may be id is pass within $post args.
            $id = $_POST['c0'] ?? $_POST[$this->action->owner->id_field] ?? null;
            if ($id && $this->action->scope === 'single') {
                $this->action->owner->tryLoad($id);
            }

            if ($errors = $this->_hasAllArguments()) {
                $js = new jsToast(['title' => 'Error', 'message' => 'Missing Arguments: ' . implode(', ', $errors), 'class' => 'error']);
            } else {
                $args = [];
                foreach ($this->action->args as $key => $val) {
                    $args[] = $_POST[$key] ?? $this->args[$key];
                }

                $return = $this->action->execute(...$args);
                $success = is_callable($this->jsSuccess) ? call_user_func_array($this->jsSuccess, [$this, $this->action->owner, $id, $return]) : $this->jsSuccess;

                $js = $this->hook('afterExecute', [$return, $id]) ?: $success ?: new jsToast('Success' . (is_string($return) ? (': ' . $return) : ''));
            }

            return $js;
        }, $urlArgs);

        return $this;
    }

    /**
     * Set jsSuccess property.
     *
     * @param array|callable $fx
     *
     * @return $this
     */
    public function setJsSuccess($fx)
    {
        $this->jsSuccess = $fx;

        return $this;
    }

    /**
     * Check if all argument values have been provided.
     */
    private function _hasAllArguments()
    {
        $errors = [];
        foreach ($this->action->args as $key => $val) {
            if (!isset($this->args[$key])) {
                $errors[] = $key;
            }
        }

        return $errors;
    }
}
