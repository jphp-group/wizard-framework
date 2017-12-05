<?php
namespace framework\web\ui;

/**
 * Class UXButton
 * @package framework\web\ui
 *
 * @property string $text
 * @property string $kind
 *
 */
class UIButton extends UILabeled
{
    const KIND_DEFAULT = 'default';
    const KIND_PRIMARY = 'primary';
    const KIND_SUCCESS = 'success';
    const KIND_INFO    = 'info';
    const KIND_WARNING = 'warning';
    const KIND_DANGER  = 'danger';
    const KIND_LINK    = 'link';

    /**
     * default, primary, success, info, warning, danger, link
     * @var string
     */
    private $kind = 'default';

    /**
     * @return string
     */
    public function uiSchemaClassName(): string
    {
        return 'Button';
    }

    /**
     * @return string
     */
    public function getKind(): string
    {
        return $this->kind;
    }

    /**
     * @param string $kind
     */
    public function setKind(string $kind)
    {
        $this->kind = $kind;
    }

    protected function addEventLink($eventType)
    {
        switch ($eventType) {
            case "action":
                $eventType = "click";
                break;
        }

        parent::addEventLink($eventType);
    }
}