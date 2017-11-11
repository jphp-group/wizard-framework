<?php
namespace framework\core;

use php\io\Stream;

/**
 * Class Application
 * @package framework\core
 */
abstract class Application extends Component
{
    private static $instance;

    /**
     * @var Settings
     */
    protected $settings;

    /**
     * Application constructor.
     */
    public function __construct()
    {
        self::$instance = $this;

        $this->initialize();
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
    public function current()
    {
        if (!static::$instance) {
            throw new \Exception("Application is not initialized");
        }

        return static::$instance;
    }
}