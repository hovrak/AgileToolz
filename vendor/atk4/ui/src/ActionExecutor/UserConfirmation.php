<?php
/**
 * Modal executor for action that required a confirmation.
 */

namespace atk4\ui\ActionExecutor;

use atk4\core\HookTrait;
use atk4\data\UserAction\Generic;
use atk4\ui\Button;
use atk4\ui\Exception;
use atk4\ui\jsExpressionable;
use atk4\ui\jsFunction;
use atk4\ui\jsToast;
use atk4\ui\Loader;
use atk4\ui\Modal;
use atk4\ui\View;

class UserConfirmation extends Modal implements jsInterface_, Interface_
{
    use HookTrait;

    /** @var null|Generic Action to execute */
    public $action = null;

    /** @var null|Loader Loader to add content to modal. */
    public $loader = null;

    /** @var string css class for loader. */
    public $loaderUi = 'ui basic segment';
    /** @var null|array|View Loader shim object or seed. */
    public $loaderShim = null;
    /** @var jsExpressionable */
    public $jsSuccess = null;

    /** @var string css class for modal size. */
    public $size = 'tiny';

    /** @var string|null */
    private $step = null;
    private $actionInitialized = false;

    /** @var Button Ok button */
    private $ok;
    /** @var Button Cancel button */
    private $cancel;

    public function init()
    {
        parent::init();
        $this->observeChanges();
        $this->addClass($this->size);

        //Add buttons to modal for next and previous.
        $btns = (new View())->addStyle(['min-height' => '24px']);
        $this->ok = $btns->add(new Button(['Ok', 'blue']));
        $this->cancel = $btns->add(new Button(['Cancel']));
        $this->add($btns, 'actions');
        $this->showActions = true;

        $this->loader = $this->add(['Loader', 'ui'   => $this->loaderUi, 'shim' => $this->loaderShim]);
        $this->loader->loadEvent = false;
        $this->loader->addClass('atk-hide-loading-content');
    }

    /**
     * Return js expression that will trigger action executor.
     *
     * @param array $urlArgs
     *
     * @throws Exception
     *
     * @return mixed
     */
    public function jsExecute(array $urlArgs)
    {
        if (!$this->actionInitialized) {
            throw new Exception('Action must be set prior to assign trigger.');
        }

        return [$this->show(), $this->loader->jsLoad($urlArgs, ['method' => 'post'])];
    }

    /**
     * Will associate executor with the action.
     *
     * @param \atk4\data\UserAction\Action $action
     *
     * @return UserConfirmation
     */
    public function setAction(\atk4\data\UserAction\Generic $action)
    {
        $this->action = $action;
        $this->title = $this->action->owner->getModelCaption();
        $this->step = $this->stickyGet('step');

        $this->actionInitialized = true;

        return $this;
    }

    public function renderView()
    {
        $id = $this->stickyGet($this->name);
        if ($id && $this->action->scope === 'single') {
            $this->action->owner->tryLoad($id);
        }

        $this->loader->set(function ($modal) {
            if ($this->step === 'exec') {
                $this->doFinal($modal);
            } else {
                $this->doConfirmation($modal);
            }
        });

        parent::renderView();
    }

    /**
     * Set modal for displaying confirmation message.
     *
     * @param View $modal
     *
     * @throws Exception
     * @throws \atk4\core\Exception
     */
    public function doConfirmation(View $modal)
    {
        $this->addConfirmation($modal);

        $modal->js(
            true,
            $this->ok->js()->on(
                'click',
                new jsFunction(
                    [
                        $this->loader->jsload(
                            [
                                'step'      => 'exec',
                                $this->name => $this->action->owner->get('id'),
                            ],
                            ['method' => 'post']
                        ),
                    ]
                )
            )
        );

        $modal->js(
            true,
            $this->cancel->js()->on(
                'click',
                new jsFunction(
                    [
                        $this->hide(),
                    ]
                )
            )
        );
    }

    /**
     * Add confirmation message to modal.
     *
     * @param View $view
     */
    protected function addConfirmation(View $view)
    {
        $view->add(['Text'])->set($this->action->getConfirmation());
    }

    /**
     * Execute action when all step are completed.
     *
     * @param View $modal
     *
     * @throws \atk4\core\Exception
     */
    protected function doFinal(View $modal)
    {
        $return = $this->action->execute([]);

        $this->_jsSequencer($modal, $this->jsGetExecute($return, $this->action->owner->id));
    }

    protected function jsGetExecute($obj, $id)
    {
        $success = is_callable($this->jsSuccess) ? call_user_func_array($this->jsSuccess, [$this, $this->action->owner, $id]) : $this->jsSuccess;

        return [
            $this->hide(),
            $this->ok->js(true)->off(),
            $this->cancel->js(true)->off(),
            $this->hook('afterExecute', [$obj, $id]) ?: $success ?: new jsToast('Success'.(is_string($obj) ? (': '.$obj) : '')),
        ];
    }

    /**
     * Create a sequence of js statement for a view.
     *
     * @param View                   $view
     * @param array|jsExpressionable $js
     *
     * @throws \atk4\core\Exception
     */
    private function _jsSequencer(View $view, $js)
    {
        if (is_array($js)) {
            foreach ($js as $jq) {
                $this->_jsSequencer($view, $jq);
            }
        } else {
            $view->js(true, $js);
        }
    }
}
