<?php
namespace framework\web\ui;

/**
 * Class UISelectControl
 * @package framework\web\ui
 *
 * @property string $selected
 * @property string $selectedText
 * @property array $items
 */
abstract class UISelectControl extends UINode
{
    /**
     * @var string
     */
    private $selected;

    /**
     * @var string
     */
    private $selectedText = '';

    /**
     * @var array
     */
    private $items = [];

    /**
     * UISelectControl constructor.
     * @param array $items
     */
    public function __construct(array $items = [])
    {
        parent::__construct();
        
        $this->setItems($items);
    }


    /**
     * @return string
     */
    public function getSelected(): string
    {
        return $this->selected;
    }

    /**
     * @param string $selected
     */
    public function setSelected(string $selected)
    {
        $this->selected = $selected;
    }

    /**
     * @return string
     */
    public function getSelectedText(): string
    {
        return $this->selectedText;
    }

    /**
     * @param string $selectedText
     */
    public function setSelectedText(string $selectedText)
    {
        $this->selectedText = $selectedText;
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param array $items
     */
    public function setItems(array $items)
    {
        $this->items = $items;
    }

    public function provideUserInput(array $data)
    {
        parent::provideUserInput($data);

        foreach (['selected', 'selectedText'] as $prop) {
            if ($data[$prop]) {
                $this->{$prop} = $data[$prop];
            }
        }
    }

    public function synchronizeUserInput(array $data)
    {
        parent::synchronizeUserInput($data);

        if (isset($data['selected'])) {
            $this->changeRemoteProperty('selected', $data['selected']);
        }
    }
}