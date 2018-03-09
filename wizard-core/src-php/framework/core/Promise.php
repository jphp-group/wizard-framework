<?php
namespace framework\core;

use php\lang\Invoker;
use php\lib\arr;

/**
 * Class Promise
 * @package framework\core
 */
class Promise
{
    private const PENDING = 0;
    private const FULFILLED = 1;
    private const REJECTED = 2;

    /**
     * @var int
     */
    private $state = 0;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @var array
     */
    protected $subscribers = [];

    /**
     * Promise constructor.
     * @param callable $executor
     */
    function __construct(callable $executor = null)
    {
        if ($executor) {
            $executor(Invoker::of([$this, 'makeFulfill']), Invoker::of([$this, 'makeReject']));
        }
    }

    private function makeFulfill($result = null)
    {
        if ($this->state !== self::PENDING) {
            throw new \Exception('This promise is already resolved, and you\'re not allowed to resolve a promise more than once');
        }

        $this->state = self::FULFILLED;
        $this->value = $result;

        foreach ($this->subscribers as $subscriber) {
            $this->invokeCallback($subscriber[0], $subscriber[1]);
        }
    }

    private function makeReject(\Throwable $error)
    {
        if ($this->state !== self::PENDING) {
            throw new \Exception('This promise is already resolved, and you\'re not allowed to resolve a promise more than once');
        }

        $this->state = self::REJECTED;
        $this->value = $error;

        foreach ($this->subscribers as $subscriber) {
            $this->invokeCallback($subscriber[0], $subscriber[2]);
        }
    }

    private function invokeCallback(Promise $subPromise, callable $callBack = null) {
            if (is_callable($callBack)) {
                try {
                    $result = $callBack($this->value);
                    if ($result instanceof self) {
                        $result->then([$subPromise, 'makeFulfill'], [$subPromise, 'makeReject']);
                    } else {
                        $subPromise->makeFulfill($result);
                    }
                } catch (\Throwable $e) {
                    $subPromise->makeReject($e);
                }
            } else {
                if ($this->state === self::FULFILLED) {
                    $subPromise->makeFulfill($this->value);
                } else {
                    $subPromise->makeReject($this->value);
                }
            }
    }

    function then(callable $onFulfilled = null, callable $onRejected = null): Promise
    {
        $subPromise = new self();

        switch ($this->state) {
            case self::PENDING:
                $this->subscribers[] = [$subPromise, $onFulfilled, $onRejected];
                break;

            case self::FULFILLED:
                $this->invokeCallback($subPromise, $onFulfilled);
                break;

            case self::REJECTED:
                $this->invokeCallback($subPromise, $onRejected);
                break;
        }
        return $subPromise;
    }

    /**
     * @param callable|null $onRejected
     * @return Promise
     */
    function catch(?callable $onRejected = null): Promise
    {
        return $this->then(null, $onRejected);
    }

    /**
     * Stops execution until this promise is resolved.
     *
     * @return mixed
     * @throws \Throwable
     */
    function wait()
    {
        while ($this->state === self::PENDING) {
            // loop.
        }

        if ($this->state === self::FULFILLED) {
            return $this->value;
        } else if ($this->state === self::REJECTED) {
            throw $this->value;
        }
    }

    /**
     * @param mixed $result
     * @return Promise
     */
    public static function resolve($result): Promise
    {
        if ($result instanceof Promise) {
            return new Promise(function ($resolve, $reject) use ($result) {
                $result->then($resolve, $reject);
            });
        } else {
            return new Promise(function ($resolve) use ($result) {
                $resolve($result);
            });
        }
    }

    /**
     * @param \Throwable $error
     * @return Promise
     */
    public static function reject(\Throwable $error): Promise
    {
        return new Promise(function ($resolve, $reject) use ($error) {
            $reject($error);
        });
    }

    /**
     * @param Promise[]|iterable $promises
     * @return Promise
     */
    public static function race(iterable $promises): Promise
    {
        $promises = flow($promises)->find(function ($p) {
            return $p instanceof Promise;
        })->toArray();

        if (!$promises) {
            return Promise::resolve(null);
        }

        return new Promise(function ($resolve, $reject) use ($promises) {
            $done = false;

            foreach ($promises as $promise) {
                if ($promise instanceof Promise) {
                    $promise->then(function ($result) use (&$done, $resolve) {
                        if (!$done) {
                            $done = true;
                            $resolve($result);
                        }
                    }, function ($error) use (&$done, $reject) {
                        if (!$done) {
                            $done = true;
                            $reject($error);
                        }
                    });
                }
            }
        });
    }

    /**
     * @param Promise[]|iterable $promises
     * @return Promise
     */
    public static function all(iterable $promises): Promise
    {
        $promises = flow($promises)->find(function ($p) {
            return $p instanceof Promise;
        })->toMap();

        if (!$promises) {
            return Promise::resolve([]);
        }

        $max = sizeof($promises);

        $results = [];
        foreach ($promises as $key => $promise) {
            $results[$key] = null;
        }

        return new Promise(function ($resolve, $reject) use ($promises, $max, $results) {
            $done = false;
            $count = 0;

            /** @var Promise $promise */
            foreach ($promises as $i => $promise) {
                $promise->then(function ($result) use (&$done, $i, $resolve, $max, &$results, &$count) {
                    if (!$done) {
                        $results[$i] = $result;
                        $count++;

                        if ($max === $count) {
                            $done = true;
                            $resolve($results);
                        }
                    }
                }, function ($error) use (&$done, $reject) {
                    if (!$done) {
                        $done = true;
                        $reject($error);
                    }
                });
            }
        });
    }
}