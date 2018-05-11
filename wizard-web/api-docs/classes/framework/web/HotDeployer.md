# HotDeployer

- **class** `HotDeployer` (`framework\web\HotDeployer`) **extends** `Component` (`framework\core\Component`)
- **source** `framework/web/HotDeployer.php`

**Description**

Class HotDeployer

---

#### Properties

- `->`[`deployHandler`](#prop-deployhandler) : `callable`
- `->`[`undeployHandler`](#prop-undeployhandler) : `callable`
- `->`[`dirWatchers`](#prop-dirwatchers) : `array`
- `->`[`sourceDirs`](#prop-sourcedirs) : `array`
- `->`[`fileWatchers`](#prop-filewatchers) : `array`
- `->`[`deployThreadPool`](#prop-deploythreadpool) : `ThreadPool`
- `->`[`env`](#prop-env) : `Environment`
- `->`[`shutdown`](#prop-shutdown) : `bool`

---

#### Methods

- `->`[`__construct()`](#method-__construct) - _HotDeployer constructor._
- `->`[`addDirWatcher()`](#method-adddirwatcher)
- `->`[`addSourceDir()`](#method-addsourcedir)
- `->`[`addFileWatcher()`](#method-addfilewatcher)
- `->`[`updateWatcherStats()`](#method-updatewatcherstats)
- `->`[`run()`](#method-run) - _Run Hot deployer._
- `->`[`undeploy()`](#method-undeploy)
- `->`[`shutdown()`](#method-shutdown) - _Shutdown all._
- `->`[`deploy()`](#method-deploy) - _Redeploy._
- `->`[`directRun()`](#method-directrun)

---
# Methods

<a name="method-__construct"></a>

### __construct()
```php
__construct(callable $deployHandler, callable $undeployHandler): void
```
HotDeployer constructor.

---

<a name="method-adddirwatcher"></a>

### addDirWatcher()
```php
addDirWatcher(string $directory, bool $source): void
```

---

<a name="method-addsourcedir"></a>

### addSourceDir()
```php
addSourceDir(string $directory): void
```

---

<a name="method-addfilewatcher"></a>

### addFileWatcher()
```php
addFileWatcher(string $file): void
```

---

<a name="method-updatewatcherstats"></a>

### updateWatcherStats()
```php
updateWatcherStats(): void
```

---

<a name="method-run"></a>

### run()
```php
run(): void
```
Run Hot deployer.

---

<a name="method-undeploy"></a>

### undeploy()
```php
undeploy(): void
```

---

<a name="method-shutdown"></a>

### shutdown()
```php
shutdown(): void
```
Shutdown all.

---

<a name="method-deploy"></a>

### deploy()
```php
deploy(): void
```
Redeploy.

---

<a name="method-directrun"></a>

### directRun()
```php
directRun(): void
```