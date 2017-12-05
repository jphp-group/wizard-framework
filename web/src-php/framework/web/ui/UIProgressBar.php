<?php
namespace framework\web\ui;

/**
 * Class UIProgressBar
 * @package framework\web\ui
 *
 * @property string $kind
 * @property bool $animated
 * @property bool $striped
 * @property float $value
 */
class UIProgressBar extends UINode
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
     * @var bool
     */
    private $animated = false;

    /**
     * @var bool
     */
    private $striped = false;

    /**
     * @var float
     */
    private $value = 0.0;

    /**
     * @return string
     */
    public function uiSchemaClassName(): string
    {
        return 'ProgressBar';
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

    /**
     * @return bool
     */
    public function isAnimated(): bool
    {
        return $this->animated;
    }

    /**
     * @param bool $animated
     */
    public function setAnimated(bool $animated)
    {
        $this->animated = $animated;
    }

    /**
     * @return bool
     */
    public function isStriped(): bool
    {
        return $this->striped;
    }

    /**
     * @param bool $striped
     */
    public function setStriped(bool $striped)
    {
        $this->striped = $striped;
    }

    /**
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }

    /**
     * @param float $value
     */
    public function setValue(float $value)
    {
        $this->value = $value;
    }
}