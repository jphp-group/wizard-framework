<?php
namespace framework\httpclient;

use framework\core\Component;
use framework\core\Event;
use framework\core\Logger;
use php\lang\Invoker;
use php\time\Timer;
use timer\AccurateTimer;

/**
 * Class HttpChecker
 * @package bundle\http
 * @packages httpclient
 *
 * @property int $checkInterval
 */
class HttpChecker extends Component
{
    /**
     * @var string
     */
    public $url;

    /**
     * @var bool
     */
    public $autoStart = true;

    /**
     * @var int
     */
    protected $_checkInterval = 10000;

    /**
     * @var HttpClient
     */
    protected $client;

    /**
     * @var Timer
     */
    protected $checkTimer;

    /**
     * @var boolean
     */
    protected $available = null;

    /**
     * ...
     */
    function __construct()
    {
        parent::__construct();

        $this->client = new HttpClient();
        $this->client->userAgent = 'Http Checker 1.0';
        $this->client->readTimeout = 15000;
    }

    /**
     * @event addTo
     * @param Event $e
     */
    private function handleAddTo(Event $e)
    {
        if ($this->autoStart) {
            $this->start();
        }
    }

    protected function doInterval()
    {
        $active = (bool) $this->checkTimer;
        $this->stop();

        Logger::debug("http checker ping '{0}' ...", $this->url);

        $this->client->getAsync($this->url, [], function (HttpResponse $response) use ($active) {
            if ($response->statusCode() == 200) {
                if ($this->available !== true) {
                    $this->trigger(new Event('online', $this));
                }

                $this->available = true;
            } else {
                if ($this->available !== false) {
                    $this->trigger(new Event('offline', $this));
                }

                $this->available = false;
            }

            if ($active) {
                $this->start();
            }
        });
    }

    /**
     * Check status of url.
     */
    public function ping()
    {
        $this->doInterval();
    }

    /**
     * Start checker worker.
     */
    public function start()
    {
        $this->checkTimer = Timer::every($this->_checkInterval, Invoker::of([$this, 'doInterval']));
        $this->ping();
    }

    /**
     * Stop checker worker.
     */
    public function stop()
    {
        if ($this->checkTimer) {
            $this->checkTimer->cancel();
            $this->checkTimer = null;
        }
    }

    /**
     * @return HttpClient
     */
    public function client()
    {
        return $this->client;
    }

    /**
     * @return int
     */
    protected function getCheckInterval()
    {
        return $this->_checkInterval;
    }

    /**
     * @param int $checkInterval
     */
    protected function setCheckInterval($checkInterval)
    {
        $this->_checkInterval = $checkInterval;
        $this->stop();
        $this->start();
    }

    /**
     * @return bool
     */
    public function isOffline()
    {
        return $this->available === false;
    }

    /**
     * @return bool
     */
    public function isOnline()
    {
        return $this->available === true;
    }

    public function isUnknown()
    {
        return $this->available === null;
    }
}