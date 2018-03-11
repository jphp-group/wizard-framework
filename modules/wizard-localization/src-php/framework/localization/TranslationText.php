<?php
namespace framework\localization;

/**
 * Class TranslationText
 * @package framework\localization
 */
class TranslationText
{
    private $value;

    /**
     * TranslationText constructor.
     * @param array|string $value
     */
    public function __construct($value = [])
    {
        $this->value = $value;
    }

    /**
     * @param string $lang
     * @return bool
     */
    public function has(string $lang): bool
    {
        return isset($this->value[$lang]);
    }

    /**
     * @param string $lang
     * @return mixed
     */
    public function get(string $lang): string
    {
        return is_array($this->value) ? ($this->value[$lang] ?? $this->value['']) : $this->value;
    }

    /**
     * @param string $lang
     * @param string $value
     */
    public function set(string $lang, string $value)
    {
        if (is_array($this->value)) {
            $this->value[$lang] = $value;
        } else {
            $this->value = [$lang => $value, '' => $this->value];
        }
    }
}