<?php
namespace framework\web;

use framework\core\Component;
use framework\web\ui\UIContainer;
use framework\web\ui\UINode;
use php\format\JsonProcessor;
use php\format\ProcessorException;
use php\io\IOException;
use php\io\Stream;

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
     * @param array $data
     * @return UINode
     * @throws \Exception
     */
    protected function _load(array $data): UINode
    {
        $type = $data['_'];

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

        foreach ($data as $key => $value) {
            if ($key[0] === '_') continue;

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
     * @return mixed
     */
    public function loadFromStream(Stream $stream, ?string $schemaKey = null)
    {
        $json = new JsonProcessor(JsonProcessor::DESERIALIZE_LENIENT | JsonProcessor::DESERIALIZE_AS_ARRAYS);
        $schema = $json->parse($stream);

        if (isset($schemaKey)) {
            $this->load((array) $schema[$schemaKey]);
        } else {
            $this->load($schema);
        }

        return $schema;
    }
}