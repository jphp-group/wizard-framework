<?php
namespace framework\core;

use php\io\IOException;
use php\io\Stream;
use php\lib\fs;
use php\lib\str;
use php\util\Configuration;

/**
 * Class Settings
 * @package framework\core
 */
class Settings
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @param Stream $stream
     * @param string $format
     */
    public function load(Stream $stream, string $format = 'ini')
    {
        $config = $stream->parseAs($format);
        $this->data = flow($this->data, $config)->toMap();
    }

    /**
     * @param string $file
     * @param string $format
     */
    public function loadFile(string $file, string $format = 'ini')
    {
        $config = fs::parseAs($file, $format);
        $this->data = flow($this->data, $config)->toMap();
    }

    /**
     * @param string $name
     * @param null $default
     * @return mixed|null
     */
    public function get(string $name, $default = null)
    {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        }

        $parts = str::split($name, '.');

        $data = $this->data;

        foreach ($parts as $part) {
            $data = $data[$part];
        }

        return $data ?? $default;
    }

    /**
     * @param string $name
     * @param $value
     */
    public function set(string $name, $value)
    {
        if ($value === null) {
            unset($this->data[$name]);
        } else {
            $this->data[$name] = $value;
        }
    }

    /**
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool
    {
        return isset($this->data[$name]);
    }
}