# Settings

- **class** `Settings` (`framework\core\Settings`)
- **source** `framework/core/Settings.php`

**Description**

Class Settings

---

#### Properties

- `->`[`data`](#prop-data) : `array`

---

#### Methods

- `->`[`load()`](#method-load)
- `->`[`loadFile()`](#method-loadfile)
- `->`[`get()`](#method-get)
- `->`[`set()`](#method-set)
- `->`[`has()`](#method-has)

---
# Methods

<a name="method-load"></a>

### load()
```php
load(php\io\Stream $stream, string $format): void
```

---

<a name="method-loadfile"></a>

### loadFile()
```php
loadFile(string $file, string $format): void
```

---

<a name="method-get"></a>

### get()
```php
get(string $name, null $default): mixed|null
```

---

<a name="method-set"></a>

### set()
```php
set(string $name, mixed $value): void
```

---

<a name="method-has"></a>

### has()
```php
has(string $name): bool
```