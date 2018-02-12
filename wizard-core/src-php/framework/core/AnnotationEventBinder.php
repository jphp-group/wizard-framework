<?php
namespace framework\core;

use php\lib\str;

/**
 * Class AnnotationEventBinder
 * @package framework\core
 */
class AnnotationEventBinder
{
    /**
     * @var object
     */
    private $context;

    /**
     * @var object
     */
    private $handler;

    /**
     * @var callable
     */
    private $lookup;

    /**
     * AnnotationEventBinder constructor.
     * @param Component $context
     * @param object $handler
     * @param callable|null $lookup
     */
    public function __construct(Component $context, object $handler, ?callable $lookup = null)
    {
        $this->context = $context;
        $this->handler = $handler;
    }

    /**
     * @param $id
     * @return Component|null
     */
    protected function lookup(string $id): ?Component
    {
        if ($id) {
            if ($this->lookup) {
                $result = call_user_func($this->lookup, $this->context, $id);
            } else {
                $result = $this->context->{$id};
            }
        } else {
            $result = $this->context;
        }

        return $result instanceof Component ? $result : null;
    }

    protected function tryBindMethod(\ReflectionMethod $method, object $context)
    {
        $binds = Annotations::getOfMethod('event', $method);

        if (!$binds) {
            return;
        }

        if (!is_array($binds)) {
            $binds = [$binds];
        }

        foreach ($binds as $bind) {
            [$id, $event] = str::split($bind, '.');

            if (!$event) {
                $event = $id;
                $id = '';
            }

            if ($id === '$owner') {
                $method->setAccessible(true);
                $key = str::uuid();

                $this->context->bind('addTo', function (Event $e) use ($event, $method, $context, $key) {
                    $e->context->on($event, function (Event $e) use ($method, $context, $e) {
                        $method->invokeArgs($context, [$e]);
                    }, $key);
                });

                $this->context->bind('removeFrom', function (Event $e) use ($event, $key) {
                   $e->context->off($event, $key);
                });

                continue;
            }

            $component = $this->lookup($id);

            if ($component) {
                $method->setAccessible(true);

                /*Logger::debug(
                    "Bind event '{0}' handle via annotation with {1}::{2}()",
                    $event, $method->getDeclaringClass()->getName(), $method->getName()
                );*/

                $component->on($event, function (Event $e) use ($method, $context) {
                    $method->invokeArgs($context, [$e]);
                });
            } else {
                Logger::warn("Unable to bind '{0}', component not found", $bind);
            }
        }
    }

    public function loadBinds()
    {
        $class = new \ReflectionClass($this->handler);

        foreach ($class->getMethods() as $method) {
            $this->tryBindMethod($method, $this->handler);
        }
    }
}