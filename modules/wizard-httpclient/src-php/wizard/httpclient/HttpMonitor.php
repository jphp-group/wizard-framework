<?php
namespace wizard\httpclient;

use framework\core\Component;
use framework\core\Event;
use framework\core\Logger;
use framework\core\Promise;
use php\lang\ThreadPool;
use php\time\Time;
use php\time\Timer;

/**
 * Class HttpMonitor
 * @package wizard\httpclient
 *
 * @property string[] $observables
 * @property string $interval
 * @property int[] $successStatuses
 * @property string $connectionTimeout
 * @property string $readTimeout
 */
class HttpMonitor extends Component
{
    /**
     * @var array
     */
    private $observables = [];

    /**
     * @var array
     */
    private $observableStatuses = [];

    /**
     * @var string
     */
    private $interval = '5s';

    /**
     * @var array
     */
    private $successStatuses = [200];

    /**
     * @var string
     */
    private $connectionTimeout = '5s';

    /**
     * @var string
     */
    private $readTimeout = '10s';

    /**
     * @var Timer
     */
    private $timer;

    /**
     * @var HttpClient
     */
    private $customClient;

    /**
     * Start watch.
     */
    public function startWatch()
    {
        if (!$this->timer) {
            $this->timer = Timer::every($this->interval, function () {
                $this->pingAndUpdate();
            });
        }
    }

    /**
     * Stop monitor watcher.
     */
    public function stopWatch()
    {
        if ($this->timer) {
            $this->timer->cancel();
            $this->timer = null;
        }
    }

    /**
     * Stop + Start
     */
    public function restartWatch()
    {
        $this->stopWatch();
        $this->startWatch();
    }

    /**
     * @return bool
     */
    public function isRunning(): bool
    {
        return (bool) $this->timer;
    }

    /**
     * @return array
     */
    public function ping(): array
    {
        return $this->pingAsync()->wait();
    }

    public function pingAsync(): Promise
    {
        $threadPool = ThreadPool::create(8, 32);

        $client = new HttpClient();
        $client->setThreadPool($threadPool);
        $client->requestType = 'URLENCODE';
        $client->responseType = 'STREAM';
        $client->connectTimeout = $this->connectionTimeout;
        $client->readTimeout = $this->readTimeout;

        Logger::debug('Ping Async');

        $promises = [];
        foreach ($this->observables as $url) {
            $promises[$url] = $client->getAsync($url);
        }

        $promise = Promise::all($promises);

        return new Promise(function ($resolve, $reject) use ($promise, $threadPool) {
             $promise->then(function (array $result) use ($resolve, $threadPool) {
                 $value = [];

                 /**
                  * @var string $url
                  * @var HttpResponse $response
                  */
                 foreach ($result as $url => $response) {
                     $one = [];

                     $one['available'] = $response->statusCode() === 200;
                     $one['status'] = $response->statusCode();
                     $one['time'] = Time::millis() - $response->time()->getTime();

                     $value[$url] = $one;
                 }

                 $resolve($value);
                 $threadPool->shutdown();
             }, function ($error) use ($reject, $threadPool) {
                 $reject($error);
                 $threadPool->shutdown();
             });
        });
    }


    /**
     * Ping and update statuses.
     *
     * @return Promise
     */
    public function pingAndUpdate(): Promise
    {
        return $this->pingAsync()->then(function (array $value) {
            foreach ($value as $url => $status) {
                $oldStatus = $this->observableStatuses[$url];

                if (isset($oldStatus)) {
                    if ($status['available'] != $oldStatus['available']) {
                        $this->trigger(new Event($status['available'] ? 'online' : 'offline', $this, null, ['url' => $url, 'status' => $status]));
                    }
                } else if (!$status['available']) {
                    $this->trigger(new Event('offline', $this, null, ['url' => $url, 'status' => $status]));
                }
            }

            $this->trigger(new Event('update', $this, null, ['statuses' => $value]));
            $this->observableStatuses = $value;
        });
    }

    /**
     * @param string $url
     * @return array|null
     */
    public function getStatus(string $url): ?array
    {
        return $this->observableStatuses[$url];
    }

    /**
     * @param string $url
     * @return bool
     */
    public function isOnline(string $url): bool
    {
        return $this->observableStatuses[$url]['available'];
    }

    /**
     * @param string $url
     * @return bool
     */
    public function isOffline(string $url): bool
    {
        return !$this->isOnline($url);
    }

    /**
     * @return bool
     */
    public function isAllOnline(): bool
    {
        return !flow($this->observableStatuses)->findOne(function ($it) {
            return !$it['available'];
        });
    }

    /**
     * @return bool
     */
    public function isAllOffline(): bool
    {
        return !$this->isAllOnline();
    }

    /**
     * @return array
     */
    protected function getObservables(): array
    {
        return $this->observables;
    }

    /**
     * @param array $observables
     */
    protected function setObservables(array $observables)
    {
        $this->observables = $observables;

        if ($this->isRunning()) {
            $this->restartWatch();
        }
    }

    /**
     * @return string
     */
    protected function getInterval(): string
    {
        return $this->interval;
    }

    /**
     * @param string $interval
     */
    protected function setInterval(string $interval)
    {
        $this->interval = $interval;

        if ($this->isRunning()) {
            $this->restartWatch();
        }
    }

    /**
     * @return array
     */
    protected function getSuccessStatuses(): array
    {
        return $this->successStatuses;
    }

    /**
     * @param array $successStatuses
     */
    protected function setSuccessStatuses(array $successStatuses)
    {
        $this->successStatuses = $successStatuses;
    }

    /**
     * @return string
     */
    protected function getConnectionTimeout(): string
    {
        return $this->connectionTimeout;
    }

    /**
     * @param string $connectionTimeout
     */
    protected function setConnectionTimeout(string $connectionTimeout)
    {
        $this->connectionTimeout = $connectionTimeout;
    }

    /**
     * @return string
     */
    protected function getReadTimeout(): string
    {
        return $this->readTimeout;
    }

    /**
     * @param string $readTimeout
     */
    protected function setReadTimeout(string $readTimeout)
    {
        $this->readTimeout = $readTimeout;
    }
}