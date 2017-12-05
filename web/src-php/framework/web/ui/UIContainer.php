<?php
namespace framework\web\ui;

use framework\web\UI;

/**
 * @package framework\web\ui
 *
 * @property UINode[] $children
 * @property string $horAlign
 * @property string $verAlign
 * @property array $align
 */
class UIContainer extends UINode
{
    /**
     * @var string
     */
    private $horAlign;

    /**
     * @var string
     */
    private $verAlign;

    /**
     * @var array
     */
    private $children = [];

    /**
     * UIContainer constructor.
     * @param UINode[] $children
     */
    public function __construct(array $children = [])
    {
        parent::__construct();

        $this->children = $children;
    }

    /**
     * @return string
     */
    public function uiSchemaClassName(): string
    {
        return 'Container';
    }

    /**
     * @return array
     */
    public function uiSchema(): array
    {
        $schema = parent::uiSchema();

        $schema['_content'] = [];

        foreach ($this->children as $child) {
            $schema['_content'][] = $child->uiSchema();
        }

        unset($schema['children']);

        return $schema;
    }

    /**
     * @param UINode $node
     */
    public function add(UINode $node)
    {
        if ($this->connectedUi) {
            $this->connectedUi->callNodeMethod($this, 'add', [$node]);
        }

        $this->children[] = $node;
        $node->__setParent($this);
        $node->connectToUI($this->connectedUi);
    }

    /**
     * @param UINode $node
     * @return bool
     */
    public function remove(UINode $node)
    {
        if ($this->connectedUi) {
            $this->connectedUi->callNodeMethod($this, 'remove', [$node]);
        }

        foreach ($this->children as $i => $child) {
            if ($child === $node) {
                $node->__setParent(null);
                $node->disconnectUi();

                unset($this->children[$i]);
                return true;
            }
        }

        return false;
    }

    /**
     * Remove All children.
     */
    public function clear()
    {
        if ($this->connectedUi) {
            $this->connectedUi->callNodeMethod($this, 'clear');
        }

        foreach ($this->children as $i => $child) {
            $child->__setParent(null);
            $child->disconnectUi();

            unset($this->children[$i]);
        }
    }

    /**
     * @return UINode[]
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * @return string
     */
    public function getHorAlign(): string
    {
        return $this->horAlign;
    }

    /**
     * @param string $horAlign
     */
    public function setHorAlign(string $horAlign)
    {
        $this->horAlign = $horAlign;
    }

    /**
     * @return string
     */
    public function getVerAlign(): string
    {
        return $this->verAlign;
    }

    /**
     * @param string $verAlign
     */
    public function setVerAlign(string $verAlign)
    {
        $this->verAlign = $verAlign;
    }

    /**
     * @return array
     */
    public function getAlign(): array
    {
        return [$this->verAlign, $this->horAlign];
    }

    /**
     * @param array $value
     */
    public function setAlign(array $value)
    {
        [$this->verAlign, $this->horAlign] = $value;
    }

    public function connectToUI(?UI $ui)
    {
        parent::connectToUI($ui);

        foreach ($this->children as $child) {
            $child->connectToUI($ui);
        }
    }
}