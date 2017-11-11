<?php
namespace framework\core;

use php\io\IOException;
use php\io\Stream;
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
     * @throws IOException
     */
    public function load(Stream $stream)
    {
        $config = new Configuration($stream);
        $this->data = $config->toArray();
    }

    /**
     * @param string $file
     * @throws IOException
     */
    public function loadFile(string $file)
    {
        $config = new Configuration($file);
        $this->data = $config->toArray();
    }

    /**
     * @param string $name
     * @param null $default
     * @return mixed|null
     */
    public function get(string $name, $default = null)
    {
        return $this->data[$name] ?? $default;
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