<?php
namespace framework\core;

use php\lib\str;

/**
 * Class Components
 * @package framework\core
 */
class Components implements \ArrayAccess, \Countable, \IteratorAggregate
{
    /**
     * @var Component
     */
    private $owner;

    /**
     * @var Component[]
     */
    private $components = [];

    /**
     * Components constructor.
     * @param Component $owner
     */
    public function __construct(?Component $owner = null)
    {
        $this->owner = $owner;
    }

    /**
     * @param string $name
     * @return Component|null
     * @throws \Exception
     */
    public function __get(string $name)
    {
        if ($c = $this->components[$name]) {
            return $c;
        }

        throw new \Exception("Failed to find component, component (id = $name) doesn't exist");
    }

    /**
     * @param string $name
     * @param Component $value
     */
    public function __set(string $name, ?Component $value)
    {
        if ($value) {
            $this->offsetSet($name, $value);
        } else {
            $this->removeById($name);
        }
    }

    /**
     * @param string $name
     * @return bool
     */
    public function __isset(string $name)
    {
        return isset($this->components[$name]);
    }

    /**
     * @param string $name
     */
    public function __unset(string $name)
    {
        $this->removeById($name);
    }

    /**
     * @param string $class
     * @return array
     */
    public function findByClass(string $class): array
    {
        $result = [];

        foreach ($this->components as $component) {
            if (is_a($component, $class)) {
                $result[$component->id] = $component;
            }
        }

        return $result;
    }

    /**
     * @param Component $component
     * @throws \Exception
     */
    public function add(Component $component)
    {
        $id = $component->id;

        if (!$id) {
            $component->id = $id = str::uuid();
        }

        if ($this->components[$id]) {
            throw new \Exception("Component with id = '$component->id' already added");
        }

        if ($this->owner) {
            $this->owner->trigger(new Event('addComponent', $this->owner, null, ['component' => $component]));
        }

        $component->__setOwner($this->owner);
        $component->trigger(new Event('addTo', $component, $this->owner));

        // on change id
        $bindId = $component->bind('change-id', function (Event $e) {
            if ($e->data['oldValue']) {
                unset($this->components[$e->data['oldValue']]);
            }

            if ($e->sender->id) {
                $this->components[$e->sender->id] = $this->add($e->sender);
            }
        });
        $component->data('--components-bind-id', $bindId);
        // ---------------


        $this->components[$id] = $component;
    }

    /**
     * @param string $id
     * @return bool
     */
    public function removeById(string $id): bool
    {
        if ($c = $this->components[$id]) {
            if ($this->owner) {
                $this->owner->trigger(new Event('removeComponent', $this->owner, null, ['component' => $c]));
            }

            $c->trigger(new Event('removeFrom', $c, $this->owner));
            $c->off('change-id', $c->data('--components-bind-id'));

            $c->__setOwner(null);

            unset($this->components[$id]);
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param Component $component
     * @return bool
     */
    public function remove(Component $component): bool
    {
        $id = $component->id;

        if ($c = $this->components[$id]) {
            if ($c === $component) {
                if ($this->owner) {
                    $this->owner->trigger(new Event('removeComponent', $this->owner, null, ['component' => $c]));
                }

                $c->trigger(new Event('removeFrom', $c, $this->owner));
                $c->off('change-id', $c->data('--components-bind-id'));
                $c->__setOwner(null);

                unset($this->components[$id]);
                return true;
            }
        }

        return false;
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->components[$offset]);
    }

    /**
     * @param mixed $offset
     * @return Component
     */
    public function offsetGet($offset)
    {
        return $this->components[$offset];
    }

    /**
     * Don't use this method directly, use the add() method for adding.
     *
     * @param mixed $offset
     * @param mixed $value
     * @throws \Exception
     */
    public function offsetSet($offset, $value)
    {
        if ($offset) {
            $this->removeById($offset);

            if ($value instanceof Component) {
                $value->id = $offset;
            }

            $this->add($value);
        } else {
            $this->add($value);
        }
    }

    /**
     * Alias of removeById().
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        $this->removeById($offset);
    }

    /**
     * @return int
     */
    public function count()
    {
        return sizeof($this->components);
    }

    /**
     * Don't use this method directly, it only for foreach syntax support.
     * @return \Iterator
     */
    public function getIterator()
    {
        return flow($this->components);
    }

    public function __debugInfo()
    {
        return $this->components;
    }


}