# Logger

- **class** `Logger` (`framework\core\Logger`)
- **source** `framework/core/Logger.php`

**Description**

Class Logger

---

#### Properties

- `->`[`writeHandlers`](#prop-writehandlers) : `mixed`
- `->`[`scopeLevels`](#prop-scopelevels) : `mixed`
- `->`[`levels`](#prop-levels) : `mixed`
- `->`[`format`](#prop-format) : `mixed`

---

#### Static Methods

- `Logger ::`[`setFormat()`](#method-setformat)
- `Logger ::`[`getFormat()`](#method-getformat)
- `Logger ::`[`addWriter()`](#method-addwriter)
- `Logger ::`[`setLevel()`](#method-setlevel)
- `Logger ::`[`getLevel()`](#method-getlevel)
- `Logger ::`[`stdoutWriter()`](#method-stdoutwriter)
- `Logger ::`[`format()`](#method-format)
- `Logger ::`[`log()`](#method-log)
- `Logger ::`[`info()`](#method-info)
- `Logger ::`[`debug()`](#method-debug)
- `Logger ::`[`trace()`](#method-trace)
- `Logger ::`[`warn()`](#method-warn)
- `Logger ::`[`error()`](#method-error)

---
# Static Methods

<a name="method-setformat"></a>

### setFormat()
```php
Logger::setFormat(string $format): void
```

---

<a name="method-getformat"></a>

### getFormat()
```php
Logger::getFormat(): string
```

---

<a name="method-addwriter"></a>

### addWriter()
```php
Logger::addWriter(callable $writeHandler, string $id): void
```

---

<a name="method-setlevel"></a>

### setLevel()
```php
Logger::setLevel(string $level): void
```

---

<a name="method-getlevel"></a>

### getLevel()
```php
Logger::getLevel(): string
```

---

<a name="method-stdoutwriter"></a>

### stdoutWriter()
```php
Logger::stdoutWriter(bool $withColor): callable
```

---

<a name="method-format"></a>

### format()
```php
Logger::format(string $type, string $message, mixed $args): string
```

---

<a name="method-log"></a>

### log()
```php
Logger::log(string $type, string $message, mixed $args): void
```

---

<a name="method-info"></a>

### info()
```php
Logger::info(string $message, mixed $args): void
```

---

<a name="method-debug"></a>

### debug()
```php
Logger::debug(string $message, mixed $args): void
```

---

<a name="method-trace"></a>

### trace()
```php
Logger::trace(string $message, mixed $args): void
```

---

<a name="method-warn"></a>

### warn()
```php
Logger::warn(string $message, mixed $args): void
```

---

<a name="method-error"></a>

### error()
```php
Logger::error(string $message, mixed $args): void
```