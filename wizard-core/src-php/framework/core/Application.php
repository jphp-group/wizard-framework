<?php
namespace framework\core;

use php\io\Stream;
use php\lib\str;
use php\time\Time;

/**
 * Class Application
 * @package framework\core
 */
abstract class Application extends Component
{
    protected static $instance;

    /**
     * @var Settings
     */
    protected $settings;

    /**
     * @var Component[]
     */
    private $singletons = [];

    /**
     * @var string
     */
    private $stamp = '';

    /**
     * @var int
     */
    private $initializeTime;

    /**
     * Application constructor.
     */
    public function __construct()
    {
        self::$instance = $this;
        $this->stamp = str::random();
        $this->initializeTime = Time::millis();

        $this->initialize();
    }

    /**
     * @return int
     */
    public function getInitializeTime(): int
    {
        return $this->initializeTime;
    }

    /**
     * @return string
     */
    public function getStamp(): string
    {
        return $this->stamp;
    }

    /**
     * @param string $class
     * @return Component
     */
    protected function getSingletonInstance(string $class): Component
    {
        if ($component = $this->singletons[$class]) {
            return $component;
        }

        $instance = new $class();
        $this->singletons[$class] = $instance;

        return $instance;
    }

    /**
     * @param string $class
     * @return Component
     */
    public function getInstance(string $class): Component
    {
        /** @var Component $instance */
        $reflectionClass = new \ReflectionClass($class);
        $singleton = Annotations::getOfClass('singleton', $reflectionClass);

        if ($singleton) {
            $instance = $this->getSingletonInstance($class);
        } else {
            $instance = $reflectionClass->newInstance();
        }

        return $instance;
    }

    /**
     * Initialize application.
     */
    protected function initialize()
    {
        $this->trigger(new Event('initialize', $this));
        $this->settings = new Settings();
        $this->settings->load(Stream::of('res://application.conf'));
    }

    /**
     * @return void
     */
    public function launch(): void
    {
        $this->trigger(new Event('launch', $this));
    }

    /**
     * @return Application
     * @throws \Exception
     */
    public static function current()
    {
        if (!static::$instance) {
            throw new \Exception("Application is not initialized");
        }

        return static::$instance;
    }
}