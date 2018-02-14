<?php

namespace framework\ide;

use framework\core\Component;
use framework\core\Logger;
use php\compress\ZipFile;
use php\io\Stream;
use php\lang\Environment;
use php\lang\Module;
use php\lib\bin;
use php\lib\fs;
use php\lib\str;

/**
 * Class IdeComponentLoader
 * @package framework\ide
 */
class IdeComponentLoader extends Component
{
    /**
     * @var Environment
     */
    private $env;

    /**
     * @var IdeComponent[]
     */
    private $components = [];

    /**
     * @var array
     */
    private $classPaths = [];

    /**
     * @var array
     */
    private $zipFiles = [];

    /**
     * IdeComponentLoader constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->reset();
    }

    /**
     * Load all components from class paths and zip files.
     */
    public function scan()
    {
        foreach ($this->zipFiles as $zipFile) {
            $zip = new ZipFile($zipFile);

            foreach ($zip->statAll() as $stat) {
                ['name' => $name, 'directory' => $directory] = $stat;

                if (!$directory && str::endsWith($name, '.php')) {
                    $name = str::sub($name, 0, str::length($name) - 4);
                    $name = str::replace($name, '/', '\\');

                    if ($this->isComponent($name)) {
                        $this->load($name);
                    }
                }
            }
        }

        foreach ($this->classPaths as $path) {
            $path = fs::normalize(fs::abs($path));

            fs::scan($path, [
                'extensions' => ['php'],
                'callback' => function ($file) use ($path) {
                    $name = fs::pathNoExt($file);
                    $name = str::sub($name, str::length($path));
                    $name = str::replace($name, '/', '\\');

                    if ($name[0] === '\\') {
                        $name = str::sub($name, 1);
                    }

                    if ($this->isComponent($name)) {
                        $this->load($name);
                    }
                }
            ]);
        }
    }

    /**
     * @param string $pathToZip
     */
    public function addZipFile(string $pathToZip)
    {
        if (isset($this->zipFiles[$pathToZip])) {
            return;
        }

        $this->zipFiles[$pathToZip] = $pathToZip;

        $this->env->execute(function () use ($pathToZip) {
            $zip = new ZipFile($pathToZip, false);

            spl_autoload_register(function ($className) use ($zip) {
                $filename = str::replace($className, '\\', '/') . '.php';

                if ($zip->has($filename)) {
                    $zip->read($filename, function (array $stats, Stream $stream) {
                        $module = new Module($stream);
                    });

                    return true;
                }

                return false;
            });
        });
    }

    /**
     * @param string $path
     */
    public function addClassPath(string $path)
    {
        if (isset($this->classPaths[$path])) {
            return;
        }

        $this->classPaths[$path] = $path;

        $this->env->execute(function () use ($path) {
            spl_autoload_register(function ($className) use ($path) {
                $filename = str::replace($className, '\\', '/') . '.php';

                if (Stream::exists("$path/$filename")) {
                    $module = new Module("$path/$filename");
                    return true;
                }

                return false;
            });
        });
    }

    /**
     * Reset environment.
     */
    public function reset()
    {
        $classPaths = $this->classPaths;
        $zipFiles = $this->zipFiles;

        $this->classPaths = [];
        $this->components = [];
        $this->zipFiles = [];
        $this->env = new Environment(null, Environment::HOT_RELOAD);

        foreach ($classPaths as $path) {
            $this->addClassPath($path);
        }

        foreach ($zipFiles as $zipFile) {
            $this->addZipFile($zipFile);
        }
    }

    /**
     * @param string $superClassName
     * @param bool $withAbstract
     * @return array
     */
    public function findComponents(string $superClassName = Component::class, bool $withAbstract = false): array
    {
        $this->scan();

        if ($superClassName === Component::class) {
            return $withAbstract
                ? $this->components
                : flow($this->components)->find(function (Component $el) { return !$el->abstract; })->withKeys()->toArray();
        }

        $result = [];

        foreach ($this->components as $name => $component) {
            if ($component->isSubclassOf($superClassName)) {
                if ($withAbstract || !$component->abstract) {
                    $result[$name] = $component;
                }
            }
        }

        return $result;
    }

    /**
     * @param string $className
     * @param string $extendClassName
     * @return bool
     */
    public function isComponent(string $className, string $extendClassName = Component::class): bool
    {
        if ($c = $this->components[$className]) {
            if ($extendClassName === Component::class) {
                return true;
            }

            return $c->isSubclassOf($extendClassName);
        }

        return $this->env->execute(function () use ($className, $extendClassName, &$result) {
            try {
                if (class_exists($className)) {
                    $ref = new \ReflectionClass($className);
                    return $ref->isSubclassOf($extendClassName);
                } else {
                    return false;
                }
            } catch (\Error $e) {
                return false;
            }
        });
    }

    /**
     * @param string $className
     * @return IdeComponent
     */
    public function reload(string $className): IdeComponent
    {
        unset($this->components[$className]);
        return $this->load($className);
    }

    /**
     * @param string $className
     * @return IdeComponent
     * @throws \Exception
     */
    public function load(string $className): IdeComponent
    {
        if ($c = $this->components[$className]) {
            return $c;
        }

        $result = [];

        Logger::info("Load component '{0}'", $className);

        $this->env->execute(function () use ($className, &$result) {
            $ref = new \ReflectionClass($className);

            if ($ref->isSubclassOf(Component::class)) {
                $defaultProperties = $ref->getDefaultProperties();

                $result['className'] = $ref->getName();
                $result['parent'] = $ref->getParentClass() ? $ref->getParentClass()->getName() : null;
                $result['namespace'] = $ref->getNamespaceName();
                $result['file'] = $ref->getFileName();
                $result['abstract'] = $ref->isAbstract();
                $result['fields'] = [];

                foreach ($ref->getProperties() as $property) {
                    $name = $property->getName();

                    if ($property->getDeclaringClass()->getName() !== $ref->getName()) {
                        continue;
                    }

                    try {
                        if ($method = $ref->getMethod("set{$name}")) {
                            $param = $method->getParameters()[0];

                            if ($param) {
                                $result['fields'][$name] = [
                                    'comment' => $ref->getDocComment(),
                                    'default' => $defaultProperties[$name],
                                    'type' => $param->getType() ? [
                                        'name' => $param->getType()->getName(),
                                        'nullable' => $param->getType()->allowsNull(),
                                        'builtin' => $param->getType()->isBuiltin()
                                    ] : [
                                        'name' => 'mixed',
                                        'nullable' => true,
                                        'builtin' => true
                                    ]
                                ];
                            }
                        }
                    } catch (\ReflectionException $e) {
                        continue;
                    }
                }
            } else {
                $result = null;
            }
        });

        if ($result === null) {
            throw new \Exception("Failed to load component {$className}");
        }

        $fields = [];
        foreach ($result['fields'] as $name => $field) {
            $fields[$name] = $f = new IdeComponentField();

            $f->properties = [
                'name' => $name,
                'default' => $field['default'],
                'type' => $field['type']
            ];
        }

        $c = new IdeComponent();
        $c->properties = [
            'className' => $result['className'],
            'parent' => $result['parent'] && $this->isComponent($result['parent']) ? $this->load($result['parent']) : null,
            'fields' => $fields,
            'abstract' => $result['abstract']
        ];

        return $this->components[$className] = $c;
    }
}