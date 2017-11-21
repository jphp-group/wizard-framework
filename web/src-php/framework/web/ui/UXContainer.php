<?php
namespace framework\web\ui;

use framework\web\UI;

/**
 * Class UXContainer
 * @package framework\web\ui
 *
 * @property UXNode[] $children
 * @property string $horAlign
 * @property string $verAlign
 * @property array $align
 */
class UXContainer extends UXNode
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

    public function add(UXNode $node)
    {
        $this->children[] = $node;
        $node->connectToUI($this->connectedUi);
    }

    /**
     * @return UXNode[]
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