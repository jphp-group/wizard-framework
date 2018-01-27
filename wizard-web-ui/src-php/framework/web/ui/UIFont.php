<?php
namespace framework\web\ui;

use framework\core\Component;
use php\lib\str;

/**
 * Class UIFont
 * @package framework\web\ui
 *
 * @property string $name
 * @property mixed $size
 * @property bool $bold
 * @property bool $italic
 * @property bool $underline
 * @property bool $linethrough
 */
class UIFont extends Component implements UIViewable
{
    /**
     * @var string
     */
    private $name = '';

    /**
     * @var mixed
     */
    private $size;

    /**
     * @var bool
     */
    private $bold = false;

    /**
     * @var bool
     */
    private $underline = false;

    /**
     * @var bool
     */
    private $linethrough = false;

    /**
     * @var bool
     */
    private $italic = false;

    /**
     * @var callable
     */
    private $onChange = null;

    /**
     * @param UINode $node
     * @param string $property
     * @param UIFont $value
     * @return UIFont
     * @internal param UIFont $font
     */
    public static function wrapper(UINode $node, string $property, UIFont $value)
    {
        $font = clone $value;
        $font->onChange = function (UIFont $font) use ($node, $property) {
            $node->{$property} = $font;
        };

        return $font;
    }

    /**
     * @return string
     */
    protected function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    protected function setName(string $name)
    {
        $this->name = $name;

        if ($this->onChange) {
            call_user_func($this->onChange, $this);
        }
    }

    /**
     * @return mixed
     */
    protected function getSize()
    {
        return $this->size;
    }

    /**
     * @param mixed $size
     */
    protected function setSize($size)
    {
        $this->size = $size;

        if ($this->onChange) {
            call_user_func($this->onChange, $this);
        }
    }

    /**
     * @return bool
     */
    protected function isBold(): bool
    {
        return $this->bold;
    }

    /**
     * @param bool $bold
     */
    protected function setBold(bool $bold)
    {
        $this->bold = $bold;

        if ($this->onChange) {
            call_user_func($this->onChange, $this);
        }
    }

    /**
     * @return bool
     */
    protected function isUnderline(): bool
    {
        return $this->underline;
    }

    /**
     * @param bool $underline
     */
    protected function setUnderline(bool $underline)
    {
        if ($this->underline = $underline) {
            $this->linethrough = false;
        }

        if ($this->onChange) {
            call_user_func($this->onChange, $this);
        }
    }

    /**
     * @return bool
     */
    public function isLinethrough(): bool
    {
        return $this->linethrough;
    }

    /**
     * @param bool $linethrough
     */
    public function setLinethrough(bool $linethrough)
    {
        if ($this->linethrough = $linethrough) {
            $this->underline = false;
        }

        if ($this->onChange) {
            call_user_func($this->onChange, $this);
        }
    }

    /**
     * @return bool
     */
    protected function isItalic(): bool
    {
        return $this->italic;
    }

    /**
     * @param bool $italic
     */
    protected function setItalic(bool $italic)
    {
        $this->italic = $italic;

        if ($this->onChange) {
            call_user_func($this->onChange, $this);
        }
    }

    /**
     * @param string|array|UIFont $value
     * @return UIFont
     * @throws \TypeError
     */
    public static function fetch($value) {
        if (is_array($value)) {
            $font = new UIFont();

            foreach (['name', 'size', 'bold', 'italic', 'underline', 'linethrough'] as $prop) {
                if (isset($value[$prop])) {
                    $font->{$prop} = $value[$prop];
                }
            }

            return $font;
        } else if ($value instanceof UIFont) {
            return $value;
        } else if (is_string($value)) {
            $value = str::split($value, ' ');

            $font = new UIFont();

            if (isset($value[0])) {
                $font->name = $value[0];
            }

            if (isset($value[1])) {
                $font->size = $value[1];
            }

            return $font;
        } else {
            throw new \TypeError("The font argument must be an array or string or UIFont instance");
        }
    }

    /**
     * @return array
     */
    function uiSchema(): array
    {
        $schema = [];

        foreach (['name', 'size', 'bold', 'italic', 'underline', 'linethrough'] as $prop) {
            $value = $this->{$prop};
            $schema[$prop] = $value;
        }

        return $schema;
    }
}