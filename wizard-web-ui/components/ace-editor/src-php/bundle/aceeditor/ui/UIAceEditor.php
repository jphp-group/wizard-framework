<?php
namespace bundle\aceeditor\ui;

use framework\web\ui\UITextInputControl;

/**
 * Class UIAceEditor
 * @package framework\web\ui
 *
 * @property string $mode
 * @property string $theme
 * @property int $tabSize
 */
class UIAceEditor extends UITextInputControl
{
    /**
     * @var string
     */
    private $mode = '';

    /**
     * @var string
     */
    private $theme = '';

    /**
     * @var int
     */
    private $tabSize = 4;

    /**
     * UIAceEditor constructor.
     * @param string $mode
     * @param string $theme
     */
    public function __construct(string $mode = "", string $theme = "")
    {
        parent::__construct();

        $this->mode = $mode;
        $this->theme = $theme;
    }

    /**
     * @return string
     */
    public function uiSchemaClassName(): string
    {
        return 'bundle.AceEditor';
    }

    /**
     * @return string
     */
    protected function getMode(): string
    {
        return $this->mode;
    }

    /**
     * @param string $mode
     */
    protected function setMode(string $mode)
    {
        $this->mode = $mode;
    }

    /**
     * @return string
     */
    protected function getTheme(): string
    {
        return $this->theme;
    }

    /**
     * @param string $theme
     */
    protected function setTheme(string $theme)
    {
        $this->theme = $theme;
    }

    /**
     * @return int
     */
    protected function getTabSize(): int
    {
        return $this->tabSize;
    }

    /**
     * @param int $tabSize
     */
    protected function setTabSize(int $tabSize)
    {
        $this->tabSize = $tabSize;
    }

    /**
     * Undo.
     */
    public function undo()
    {
        $this->callRemoteMethod('callEditorCommand', [__FUNCTION__, []]);
    }

    /**
     * Redo.
     */
    public function redo()
    {
        $this->callRemoteMethod('callEditorCommand', [__FUNCTION__, []]);
    }

    /**
     * Attempts to center the current selection on the screen.
     */
    public function centerSelection()
    {
        $this->callRemoteMethod('callEditorCommand', [__FUNCTION__, []]);
    }

    /**
     * Empties the selection (by de-selecting it). This function also emits the 'changeSelection' event.
     */
    public function clearSelection()
    {
        $this->callRemoteMethod('callEditorCommand', [__FUNCTION__, []]);
    }

    /**
     *
     */
    public function duplicateSelection()
    {
        $this->callRemoteMethod('callEditorCommand', [__FUNCTION__, []]);
    }

    /**
     * Moves the cursor to the specified line number, and also into the indicated column.
     * @param int $lineNumber
     * @param int $column
     * @param bool $animated
     */
    public function gotoLine(int $lineNumber, int $column = 0, $animated = true)
    {
        $this->callRemoteMethod('callEditorCommand', [__FUNCTION__, [$lineNumber, $column, $animated]]);
    }

    /**
     * Shifts the document to wherever "page down" is, as well as moving the cursor position.
     */
    public function gotoPageDown()
    {
        $this->callRemoteMethod('callEditorCommand', [__FUNCTION__, []]);
    }

    /**
     * Shifts the document to wherever "page up" is, as well as moving the cursor position.
     */
    public function gotoPageUp()
    {
        $this->callRemoteMethod('callEditorCommand', [__FUNCTION__, []]);
    }

    /**
     * Indents the current line.
     */
    public function indent()
    {
        $this->callRemoteMethod('callEditorCommand', [__FUNCTION__, []]);
    }

    /**
     * @param string $text
     */
    public function insert(string $text)
    {
        $this->callRemoteMethod('callEditorCommand', [__FUNCTION__, [$text]]);
    }

    /**
     * Moves the cursor to the specified row and column. Note that this does not de-select the current selection.
     * @param int $row
     * @param int $column
     */
    public function moveCursorTo(int $row, int $column)
    {
        $this->callRemoteMethod('callEditorCommand', [__FUNCTION__, [$row, $column]]);
    }

    /**
     * Removes all the lines in the current selection.
     */
    public function removeLines()
    {
        $this->callRemoteMethod('callEditorCommand', [__FUNCTION__, []]);
    }

    /**
     * Selects all the text in editor.
     */
    public function selectAll()
    {
    }
}