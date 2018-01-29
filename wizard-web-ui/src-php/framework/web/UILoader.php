<?php
namespace framework\web;

use framework\core\Component;
use framework\web\ui\UIContainer;
use framework\web\ui\UINode;
use php\format\JsonProcessor;
use php\format\ProcessorException;
use php\format\YamlProcessor;
use php\io\IOException;
use php\io\Stream;
use php\lib\fs;

/**
 * Class UILoader
 * @package framework\web
 */
class UILoader extends Component
{
    /**
     * @var UINode
     */
    private $node;

    /**
     * @var UINode[]
     */
    private $subNodes = [];

    /**
     * @var array
     */
    private $components = [];

    /**
     * @param array $data
     * @param string $componentName
     * @return array
     * @throws \Exception
     */
    protected function extend(array $data, string $componentName): array
    {
        $component = $this->components[$componentName];

        if ($component) {
            foreach ($component as $key => $value) {
                if (!isset($data[$key]) || $key === '_') {
                    $data[$key] = $value;
                } else if (is_array($value)) {
                    foreach ($value as $k => $v) {
                        if (!isset($data[$key][$k])) {
                            $data[$key][$k] = $v;
                        }
                    }
                }
            }

            return $data;
        } else {
            throw new \Exception("Component '$componentName' not found");
        }
    }

    public function import(array $data)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                if (isset($this->components[$key])) {
                    throw new \Exception("Failed to import '$key' component, already imported");
                }

                $this->components[$key] = $value;
            } else {
                throw new \Exception("Failed to import '$key' component, invalid data");
            }
        }
    }

    /**
     * @param array $data
     * @return UINode
     * @throws \Exception
     */
    protected function _load(array $data): UINode
    {
        $type = $data['_'];

        if ($this->components[$type]) {
            $data = $this->extend($data, $type);

            $type = $data['_'];
        }

        if (!$type) {
            throw new \Exception("Type is not defined in '_' property!");
        }

        if (!class_exists($type)) {
            $newType = "framework\\web\\ui\\UI{$type}";

            if (!class_exists($newType)) {
                throw new \Exception("Type '${type}' is not defined");
            }

            $type = $newType;
        }

        $node = new $type();

        if (isset($data['behaviours'])) {

            unset($data['behaviours']);
        }

        foreach ($data as $key => $value) {
            if ($key[0] === '_') continue;

            if (isset($value['_'])) {
                $value = $this->_load($value);
            }

            $node->{$key} = $value;
        }

        if ($node instanceof UIContainer && $data['_content']) {
            foreach ((array)$data['_content'] as $one) {
                $node->add($this->_load($one));
            }
        }

        if ($node->id) {
            $this->subNodes[$node->id] = $node;
        }

        return $node;
    }

    /**
     * @return UINode
     */
    public function getNode(): ?UINode
    {
        return $this->node;
    }

    /**
     * @return UINode[]
     */
    public function getNodesById(): array
    {
        return $this->subNodes;
    }

    /**
     * @param array $data
     */
    public function load(array $data): void
    {
        $this->subNodes = [];
        $this->node = $this->_load($data);
    }

    /**
     * @param Stream $stream
     * @param null|string $schemaKey
     * @param string $format
     * @return mixed
     */
    public function loadFromStream(Stream $stream, ?string $schemaKey = null, string $format = 'json')
    {
        switch ($format) {
            case "json":
                $flags = JsonProcessor::DESERIALIZE_LENIENT | JsonProcessor::DESERIALIZE_AS_ARRAYS; break;

            default:
                $flags = 0; break;
        }

        $schema = $stream->parseAs($format, $flags);

        if (isset($schemaKey)) {
            $this->import((array) $schema['components']);
            $this->load((array) $schema[$schemaKey]);
        } else {
            $this->load($schema);
        }

        return $schema;
    }
}