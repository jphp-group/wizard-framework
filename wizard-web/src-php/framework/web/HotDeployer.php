<?php
namespace framework\web;

use framework\core\Component;
use framework\core\Logger;
use php\lang\Environment;
use php\lang\IllegalArgumentException;
use php\lang\InterruptedException;
use php\lang\Thread;
use php\lang\ThreadPool;
use php\lib\fs;
use php\lib\str;
use php\time\Timer;

/**
 * Class HotDeployer
 * @package framework\web
 */
class HotDeployer extends Component
{
    /**
     * @var callable
     */
    private $deployHandler;

    /**
     * @var callable
     */
    private $undeployHandler;

    /**
     * @var array
     */
    private $dirWatchers = [];

    /**
     * @var array
     */
    private $sourceDirs = [];

    /**
     * @var array
     */
    private $fileWatchers = [];

    /**
     * @var ThreadPool
     */
    private $deployThreadPool;

    /**
     * @var Environment
     */
    private $env;

    /**
     * @var bool
     */
    private $shutdown = false;

    /**
     * HotDeployer constructor.
     * @param callable $deployHandler
     * @param callable $undeployHandler
     */
    public function __construct(callable $deployHandler, callable $undeployHandler)
    {
        parent::__construct();

        $this->deployHandler = $deployHandler;
        $this->undeployHandler = $undeployHandler;
        $this->deployThreadPool = ThreadPool::createSingle();

        Logger::addWriter(Logger::stdoutWriter(true));
    }

    /**
     * @param string $directory
     * @param bool $source
     * @throws IllegalArgumentException
     */
    public function addDirWatcher(string $directory, bool $source = true)
    {
        /*if (!fs::isDir($directory)) {
            throw new IllegalArgumentException("Directory not found - $directory");
        }*/

        $this->dirWatchers[$directory] = [
            'dir' => $directory,
            'time' => fs::time($directory),
            'files'  => []
        ];

        if ($source) {
            $this->addSourceDir($directory);
        }
    }

    /**
     * @param string $directory
     * @throws IllegalArgumentException
     */
    public function addSourceDir(string $directory)
    {
        /*if (!fs::isDir($directory)) {
            throw new IllegalArgumentException("Directory not found - $directory");
        }*/

        $this->sourceDirs[$directory] = $directory;
    }

    /**
     * @param string $file
     * @throws IllegalArgumentException
     */
    public function addFileWatcher(string $file)
    {
        $this->fileWatchers[$file] = [
            'file' => $file,
            'time' => fs::time($file)
        ];
    }

    protected function updateWatcherStats()
    {
        foreach ($this->fileWatchers as $file => $watcher) {
            $this->fileWatchers[$file]['time'] = fs::time($file);
        }

        foreach ($this->dirWatchers as $dir => $watcher) {
            $this->dirWatchers[$dir]['files'] = [];

            fs::scan($dir, function ($filename) use ($dir) {
                $filename = "$filename";
                $this->dirWatchers[$dir]['files'][$filename] = ['time' => fs::time($filename)];
            });
        }
    }

    /**
     * Run Hot deployer.
     */
    public function run()
    {
        $timer = new Thread(function () {
            while (true) {
                if ($this->shutdown) break;

                try {
                    foreach ($this->fileWatchers as $file => $watcher) {
                        if ($this->shutdown) break;

                        $time = fs::time($file);

                        if ($watcher['time'] !== $time) {
                            Logger::debug("Watcher detect file change: {0}", $file);
                            throw new InterruptedException();
                        }
                    }

                    foreach ($this->dirWatchers as $dir => $watcher) {
                        if ($this->shutdown) break;
                        fs::scan($dir, function ($filename) use ($watcher) {
                            $filename = "$filename";
                            $time = fs::time($filename);

                            if ($oldStat = $watcher['files'][$filename]) {
                                if ($oldStat['time'] !== $time) {
                                    Logger::debug("Watcher detect file change: {0}", $filename);
                                    throw new InterruptedException();
                                }
                            } else {
                                Logger::debug("Watcher detect new file: {0}", $filename);
                                throw new InterruptedException();
                            }
                        });
                    }
                } catch (InterruptedException $e) {
                    if ($this->shutdown) break;

                    $this->updateWatcherStats();

                    $this->undeploy();

                    $this->deployThreadPool->submit(function () {
                        $this->deploy();
                    });
                } finally {
                    Thread::sleep(500);
                }
            }
        });
        $timer->start();

        $this->deployThreadPool->submit(function () {
            $this->deploy();
        });
        //$this->updateWatcherStats();
    }

    public function undeploy()
    {
        if ($this->env) {
            Logger::info("Un-deploy old env ({0})", spl_object_hash($this->env));
            $this->env->execute($this->undeployHandler);
            Logger::info("-------------------------------------------------");
        }
    }

    /**
     * Shutdown all.
     */
    public function shutdown()
    {
        $this->shutdown = true;

        $this->undeploy();
        $this->deployThreadPool->shutdown();

        Logger::info("Shutdown Deployer is done.");
    }

    /**
     * Redeploy.
     */
    public function deploy()
    {
        if ($this->shutdown) return;

        $this->env = $env = new Environment(null, Environment::CONCURRENT | Environment::HOT_RELOAD);

        $dirs = $this->sourceDirs;

        $env->execute(function () use ($dirs) {
            ob_implicit_flush(1);

            spl_autoload_register(function ($className) use ($dirs) {
                $filename = str::replace($className, '\\', '/') . '.php';

                foreach ($dirs as $dir) {
                    if (fs::isFile("$dir/$filename")) {
                        require "$dir/$filename";
                        return true;
                    }
                }

                return false;
            });
        });
        $env->importAutoLoaders();

        Logger::info("Deploy new env ({0}) ...", spl_object_hash($this->env));

        $env->execute($this->deployHandler);
    }

    public function directRun()
    {
        call_user_func($this->deployHandler);
    }
}