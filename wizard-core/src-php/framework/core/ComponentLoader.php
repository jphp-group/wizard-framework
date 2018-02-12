<?php
namespace framework\core;
use php\format\Processor;
use php\format\ProcessorException;
use php\io\IOException;
use php\io\Stream;
use php\lib\reflect;

/**
 * Class ComponentLoader
 * @package framework\core
 */
class ComponentLoader
{
    /**
     * @var array
     */
    private $macros = [];

    /**
     * @param string $type
     * @param array $data
     */
    public function addMacro(string $type, array $data)
    {
        $this->macros[$type] = $data;
    }

    /**
     * @param string $type
     * @return bool
     */
    public function isMacro(string $type): bool
    {
        return isset($this->macros[$type]);
    }

    /**
     * @param string $type
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function applyMacro(string $type, array $data): array
    {
        $macro = $this->macros[$type];

        if ($macro) {
            $result = array_merge_recursive($macro, $data);

            $type = $result['_'] = $macro['_'];

            if ($this->isMacro($type)) {
                $result = $this->applyMacro($type, $result);
            }

            return $result;
        } else {
            throw new \Exception("Macro '$type' is not found");
        }
    }

    /**
     * Create component by class name and load its description from file.
     *
     * @param string $componentClass
     * @param bool $loadDescription
     * @return Component
     * @throws \Exception
     */
    public function create(string $componentClass, bool $loadDescription = true): Component
    {
        $c = new $componentClass();

        if ($c instanceof Component) {
            if ($loadDescription) {
                foreach (['json', 'yml'] as $format) {
                    if (Processor::isRegistered($format)) {
                        $file = reflect::typeModule(reflect::typeOf($this))->getName() . ".$format";

                        try {
                            $this->loadFromFile($file, $format, $this);
                            break;
                        } catch (IOException $e) {
                            // nop.
                        }
                    }
                }
            }

            return $c;
        } else {
            throw new \Exception("ComponentClass (#1 argument) must extends " . __CLASS__);
        }
    }

    /**
     * @param array $data
     * @param Component|null $destination
     * @return Component
     * @throws \Exception
     */
    public function load(array $data, ?Component $destination = null): Component
    {
        if ($destination) {
            $c = $destination;
        } else {
            if ($this->isMacro($data['_'])) {
                $data = $this->applyMacro($data['_'], $data);
            }

            if ($type = $data['_']) {
                $c = new $type;

                if ($c instanceof Component) {
                    // nop.
                } else {
                    throw new \Exception("Type of data must class extends " . __CLASS__);
                }
            } else {
                throw new \Exception("Data must have type as '_' key");
            }
        }

        unset($data['_']);

        if ($components = $data['components']) {
            unset($data['components']);
        }

        foreach ($data as $prop => $value) {
            $c->{$prop} = $value;
        }

        if (is_iterable($components)) {
            foreach ($components as $sub) {
                $c->components->add($this->load($sub));
            }
        }

        return $c;
    }

    /**
     * @param string $file
     * @param string $format
     * @param Component|null $destination
     * @return Component
     * @throws IOException
     * @throws ProcessorException
     */
    public function loadFromFile(string $file, string $format, ?Component $destination = null): Component
    {
        $c = null;

        Stream::tryAccess($file, function (Stream $stream) use ($format, &$c, $destination) {
            $data = $stream->parseAs($format);
            $c = $this->load($data, $destination);
        });

        return $c;
    }
}