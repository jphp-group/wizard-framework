# ComponentLoader

- **class** `ComponentLoader` (`framework\core\ComponentLoader`)
- **source** `framework/core/ComponentLoader.php`

**Description**

Class ComponentLoader

---

#### Properties

- `->`[`macros`](#prop-macros) : `array`

---

#### Methods

- `->`[`addMacro()`](#method-addmacro)
- `->`[`isMacro()`](#method-ismacro)
- `->`[`applyMacro()`](#method-applymacro)
- `->`[`create()`](#method-create) - _Create component by class name and load its description from file._
- `->`[`load()`](#method-load)
- `->`[`loadFromFile()`](#method-loadfromfile)

---
# Methods

<a name="method-addmacro"></a>

### addMacro()
```php
addMacro(string $type, array $data): void
```

---

<a name="method-ismacro"></a>

### isMacro()
```php
isMacro(string $type): bool
```

---

<a name="method-applymacro"></a>

### applyMacro()
```php
applyMacro(string $type, array $data): array
```

---

<a name="method-create"></a>

### create()
```php
create(string $componentClass, bool $loadDescription): framework\core\Component
```
Create component by class name and load its description from file.

---

<a name="method-load"></a>

### load()
```php
load(array $data, [ framework\core\Component $destination): framework\core\Component
```

---

<a name="method-loadfromfile"></a>

### loadFromFile()
```php
loadFromFile(string $file, string $format, [ framework\core\Component $destination): framework\core\Component
```