<?php

namespace framework\web;

use framework\core\Event;
use framework\core\Logger;
use framework\web\ui\UIAlert;
use framework\web\ui\UINode;
use framework\web\ui\UIVBox;
use php\time\Timer;

/**
 * Class AppUI
 * @package framework\web
 *
 * @property UIForm $currentForm
 * @property string $hash
 * @property WebConsole $console
 */
class AppUI extends UI
{
    /**
     * @var UIForm
     */
    protected $currentForm;

    /**
     * @var UIForm[]
     */
    protected $forms = [];

    /**
     * @var UIForm
     */
    protected $urlForms = [];

    /**
     * @var UIForm
     */
    protected $notFoundForm;

    /**
     * @var WebConsole
     */
    protected $console;

    /**
     * AppUI constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->console = new WebConsole($this);

        $this->on('ready', function (Event $e) {
            $this->detectCurrentForm();

            if ($this->currentForm) {
                $this->currentForm->connectToUI($this);
                $this->currentForm->onNavigate->trigger();
            }
        }, __CLASS__);

        $this->setAlertFunction(function ($message, array $options) {
            $alert = new UIAlert($options['type'] ?? 'info');

            $alert->preFormatted = $options['pre'];
            $alert->text = $message;
            $alert->title = $options['title'] ?? 'Message';
            $alert->show();
        });
    }

    /**
     * @param string $code
     * @return UIForm|null
     */
    public function form(string $code): ?UIForm
    {
        $form = $this->forms[$code];

        if ($form) {
            $form->connectToUI($this);
        }

        return $form;
    }

    /**
     * @param string $code
     * @param UIForm $form
     */
    public function registerForm(string $code, UIForm $form)
    {
        $this->forms[$code] = $form;

        foreach ($form->getRoutePaths() as $routeUrl) {
            $this->urlForms[$routeUrl] = $form;
        }
    }

    /**
     * @param string $code
     * @param UIForm $form
     */
    public function registerNotFoundForm(string $code, UIForm $form)
    {
        $this->registerForm($code, $form);

        $this->notFoundForm = $form;
    }

    /**
     * @return WebConsole
     */
    protected function getConsole(): WebConsole
    {
        return $this->console;
    }

    /**
     * @return UINode
     */
    protected function makeView(): UINode
    {
        return new UIVBox();
    }

    /**
     * @return UINode
     */
    public function getView(): UINode
    {
        return $this->currentForm ? $this->currentForm->layout : parent::getView();
    }


    /**
     * @return UIForm|null
     */
    public function getCurrentForm(): ?UIForm
    {
        return $this->currentForm;
    }

    public function renderView()
    {
        $this->sendMessage('ui-render', [
            'schema' => $this->getUISchema()
        ], function () {
            Timer::after(100, function () {
                if ($this->currentForm) {
                    $this->currentForm->onShow->trigger();
                }
            });
        });
    }

    protected function detectCurrentForm()
    {
        /** @var UIVBox $view */
        if ($view = $this->getView()) {
            $view->disconnectUI();
        }

        $this->currentForm = null;

        $subPath = "/" . $this->location['contextUrl'];

        $found = false;

        foreach ($this->urlForms as $url => $form) {
            if ($url === $subPath) {
                $this->currentForm = $form;
                $found = true;
                break;
            }
        }

        if (!$found && $this->notFoundForm) {
            $this->currentForm = $this->notFoundForm;
        }

        if ($this->currentForm) {
            $this->currentForm->connectToUI($this);

            $this->sendMessage('page-set-properties', ['title' => $this->currentForm->title]);
        }
    }

    /**
     * @param $formOrCode
     * @param array $args
     */
    public function navigateTo($formOrCode, array $args = [])
    {
        if ($formOrCode instanceof UIForm) {
            $form = $formOrCode;
        } else {
            $form = $this->forms[$formOrCode];

            if (!$form) {
                Logger::error("Failed navigate to the '{0}' form, form is not found or registered.", $formOrCode);
                return;
            }
        }

        if ($this->currentForm) {
            $this->currentForm->trigger(new Event('leave', $this->currentForm, $this));
        }

        if ($form) {
            $routePath = $form->getRoutePath();

            if (isset($args['hash'])) {
                $this->hash = $args['hash'];
            }

            if ($routePath && $form !== $this->notFoundForm && !($formOrCode instanceof UIForm)) {
                $this->sendMessage('history-push', [
                    'title' => $form->title,
                    'url' => "{$this->getRoutePath()}{$routePath}",
                    'hash' => $args['hash'],
                ]);
            } else {
                $this->sendMessage('page-set-properties', [
                    'title' => $form->title,
                    'hash' => $args['hash'],
                ]);
            }

            $form->connectToUI($this);
        }

        $this->currentForm = $form;
        $this->renderView();

        $form->onNavigate->trigger($args);
    }
}