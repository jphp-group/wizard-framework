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
     * AnnotationEventBinder constructor.
     * @param Component $context
     * @param object $handler
     */
    public function __construct(Component $context, object $handler)
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
            $result = $this->context->{$id};
        } else {
            $result = $this->context;
        }

        return $result instanceof Component ? $result : null;
    }

    protected function tryBindMethod(\ReflectionMethod $method, object $context)
    {
        $binds = Annotations::getOfMethod('event', $method);

        if (!is_array($binds)) {
            $binds = [$binds];
        }

        foreach ($binds as $bind) {
            [$id, $event] = str::split($bind, '.');

            if (!$event) {
                $event = $id;
                $id = '';
            }

            $component = $this->lookup($id);

            if ($component) {
                $method->setAccessible(true);

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