<?php
namespace framework\web\ui;

/**
 * Class UIImageView
 * @package framework\web\ui
 *
 * @property string $source
 * @property bool $centered
 * @property string $displayType
 */
class UIImageView extends UINode
{
    /**
     * @var string
     */
    private $source;

    /**
     * @var bool
     */
    private $centered = false;

    /**
     * filled, cropped, resized, origin
     * @var string
     */
    private $displayType = 'origin';

    /**
     * UIImageView constructor.
     * @param string $source
     */
    public function __construct(string $source = '')
    {
        parent::__construct();

        $this->source = $source;
    }

    /**
     * @return string
     */
    public function uiSchemaClassName(): string
    {
        return 'ImageView';
    }

    /**
     * @return string
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * @param string $source
     */
    public function setSource(string $source)
    {
        $this->source = $source;
    }

    /**
     * @return bool
     */
    public function isCentered(): bool
    {
        return $this->centered;
    }

    /**
     * @param bool $centered
     */
    public function setCentered(bool $centered)
    {
        $this->centered = $centered;
    }

    /**
     * @return string
     */
    public function getDisplayType(): string
    {
        return $this->displayType;
    }

    /**
     * @param string $displayType
     */
    public function setDisplayType(string $displayType)
    {
        $this->displayType = $displayType;
    }
}