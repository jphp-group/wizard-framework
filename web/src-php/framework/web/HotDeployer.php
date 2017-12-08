<?php
namespace framework\web;

use framework\core\Component;
use framework\core\Logger;
use php\lang\Environment;
use php\lang\IllegalArgumentException;
use php\lang\InterruptedException;
use php\lang\Thread;
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
     * @var Environment
     */
    private $env;

    /**
     * HotDeployer constructor.
     * @param callable $deployHandler
     * @param callable $undeployHandler
     */
    public function __construct(callable $deployHandler, callable $undeployHandler)
    {
        $this->deployHandler = $deployHandler;
        $this->undeployHandler = $undeployHandler;

        Logger::addWriter(Logger::stdoutWriter(true));
    }

    /**
     * @param string $directory
     * @param bool $source
     * @throws IllegalArgumentException
     */
    public function addDirWatcher(string $directory, bool $source = true)
    {
        if (!fs::isDir($directory)) {
            throw new IllegalArgumentException("Directory not found - $directory");
        }

        $this->dirWatchers[$directory] = [
            'dir' => $directory,
            'time' => fs::time($directory),
            'files'  => [],
            'source' => $source
        ];
    }

    protected function updateWatcherStats()
    {
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
                try {
                    foreach ($this->dirWatchers as $dir => $watcher) {
                        fs::scan($dir, function ($filename) use ($watcher) {
                            $filename = "$filename";
                            $time = fs::time($filename);

                            if ($oldStat = $watcher['files'][$filename]) {
                                if ($oldStat['time'] !== $time) {
                                    throw new InterruptedException();
                                }
                            } else {
                                throw new InterruptedException();
                            }
                        });
                    }
                } catch (InterruptedException $e) {
                    $this->updateWatcherStats();

                    (new Thread(function () {
                        $this->redeploy();
                    }))->start();
                } finally {
                    sleep(1);
                }
            }
        });
        $timer->start();

        //$this->redeploy();
        //$this->updateWatcherStats();
    }

    /**
     * Redeploy.
     */
    public function redeploy()
    {
        if ($this->env) {
            Logger::info("Un-deploy old env ({0})", spl_object_hash($this->env));
            $this->env->execute($this->undeployHandler);
            Logger::info("-------------------------------------------------");
        }

        $this->env = $env = new Environment(null, Environment::CONCURRENT | Environment::HOT_RELOAD);

        $dirs = flow($this->dirWatchers)->withKeys()->find(function ($watcher) { return $watcher['source']; })->toArray();

        $env->execute(function () use ($dirs) {
            spl_autoload_register(function ($className) use ($dirs) {
                var_dump($className);
                $filename = str::replace($className, '\\', '/') . '.php';

                foreach ($dirs as $dir => $watcher) {
                    if ($watcher['source'] && fs::isFile("$dir/$filename")) {
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
}