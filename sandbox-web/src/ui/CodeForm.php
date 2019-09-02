<?php


namespace ui;


use bundle\aceeditor\AceEditorModule;
use bundle\aceeditor\ui\UIAceEditor;
use framework\web\ui\UIComboBox;
use framework\web\UIForm;

/**
 * Class CodeForm
 * @package ui
 *
 * @property UIAceEditor editor
 * @property UIComboBox themes
 * @property UIComboBox mode
 *
 * @path /code
 */
class CodeForm extends UIForm
{
    public function __construct()
    {
        parent::__construct();

        $this->themes->items = AceEditorModule::$themes;
        $this->mode->items = AceEditorModule::$modes;
    }

    /**
     * @event themes.change
     * @event mode.change
     */
    public function onChange() {
        $this->editor->mode = $this->mode->selectedText;
        $this->editor->theme = $this->themes->selectedText;
    }
}