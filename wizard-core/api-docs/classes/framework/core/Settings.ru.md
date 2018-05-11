# Settings

- **класс** `Settings` (`framework\core\Settings`)
- **исходники** `framework/core/Settings.php`

**Описание**

Class Settings

---

#### Свойства

- `->`[`data`](#prop-data) : `array`

---

#### Методы

- `->`[`load()`](#method-load)
- `->`[`loadFile()`](#method-loadfile)
- `->`[`get()`](#method-get)
- `->`[`set()`](#method-set)
- `->`[`has()`](#method-has)

---
# Методы

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