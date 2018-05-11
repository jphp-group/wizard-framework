# WebConsole

- **class** `WebConsole` (`framework\web\WebConsole`)
- **source** `framework/web/WebConsole.php`

**Description**

Class WebConsole

---

#### Properties

- `->`[`ui`](#prop-ui) : [`UI`](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/UI.md)

---

#### Methods

- `->`[`__construct()`](#method-__construct) - _WebConsole constructor._
- `->`[`_log()`](#method-_log)
- `->`[`clear()`](#method-clear) - _Clear console._
- `->`[`log()`](#method-log)
- `->`[`info()`](#method-info)
- `->`[`warn()`](#method-warn)
- `->`[`error()`](#method-error)
- `->`[`debug()`](#method-debug)
- `->`[`trace()`](#method-trace)

---
# Methods

<a name="method-__construct"></a>

### __construct()
```php
__construct(framework\web\UI $ui): void
```
WebConsole constructor.

---

<a name="method-_log"></a>

### _log()
```php
_log(string $type, string $message, mixed $args): void
```

---

<a name="method-clear"></a>

### clear()
```php
clear(): void
```
Clear console.

---

<a name="method-log"></a>

### log()
```php
log(string $message, mixed $args): void
```

---

<a name="method-info"></a>

### info()
```php
info(string $message, mixed $args): void
```

---

<a name="method-warn"></a>

### warn()
```php
warn(string $message, mixed $args): void
```

---

<a name="method-error"></a>

### error()
```php
error(string $message, mixed $args): void
```

---

<a name="method-debug"></a>

### debug()
```php
debug(string $message, mixed $args): void
```

---

<a name="method-trace"></a>

### trace()
```php
trace(string $message, mixed $args): void
```