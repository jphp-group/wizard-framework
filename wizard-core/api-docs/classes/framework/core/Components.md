# Components

- **class** `Components` (`framework\core\Components`)
- **source** `framework/core/Components.php`

**Description**

Class Components

---

#### Properties

- `->`[`owner`](#prop-owner) : [`Component`](https://github.com/jphp-group/wizard-framework/blob/master/wizard-core/api-docs/classes/framework/core/Component.md)
- `->`[`components`](#prop-components) : `Component[]`

---

#### Methods

- `->`[`__construct()`](#method-__construct) - _Components constructor._
- `->`[`__get()`](#method-__get)
- `->`[`__set()`](#method-__set)
- `->`[`__isset()`](#method-__isset)
- `->`[`__unset()`](#method-__unset)
- `->`[`findByClass()`](#method-findbyclass)
- `->`[`addAll()`](#method-addall)
- `->`[`setAll()`](#method-setall)
- `->`[`add()`](#method-add)
- `->`[`removeById()`](#method-removebyid)
- `->`[`clear()`](#method-clear) - _Remove all components._
- `->`[`remove()`](#method-remove)
- `->`[`offsetExists()`](#method-offsetexists)
- `->`[`offsetGet()`](#method-offsetget)
- `->`[`offsetSet()`](#method-offsetset) - _Don't use this method directly, use the add() method for adding._
- `->`[`offsetUnset()`](#method-offsetunset) - _Alias of removeById()._
- `->`[`count()`](#method-count)
- `->`[`getIterator()`](#method-getiterator) - _Don't use this method directly, it only for foreach syntax support._
- `->`[`__debugInfo()`](#method-__debuginfo)

---
# Methods

<a name="method-__construct"></a>

### __construct()
```php
__construct([ framework\core\Component $owner): void
```
Components constructor.

---

<a name="method-__get"></a>

### __get()
```php
__get(string $name): Component|null
```

---

<a name="method-__set"></a>

### __set()
```php
__set(string $name, [ framework\core\Component $value): void
```

---

<a name="method-__isset"></a>

### __isset()
```php
__isset(string $name): bool
```

---

<a name="method-__unset"></a>

### __unset()
```php
__unset(string $name): void
```

---

<a name="method-findbyclass"></a>

### findByClass()
```php
findByClass(string $class): array
```

---

<a name="method-addall"></a>

### addAll()
```php
addAll(Component[] $components): void
```

---

<a name="method-setall"></a>

### setAll()
```php
setAll(Component[] $components): void
```

---

<a name="method-add"></a>

### add()
```php
add(framework\core\Component $component): void
```

---

<a name="method-removebyid"></a>

### removeById()
```php
removeById(string $id): bool
```

---

<a name="method-clear"></a>

### clear()
```php
clear(): void
```
Remove all components.

---

<a name="method-remove"></a>

### remove()
```php
remove(framework\core\Component $component): bool
```

---

<a name="method-offsetexists"></a>

### offsetExists()
```php
offsetExists(mixed $offset): bool
```

---

<a name="method-offsetget"></a>

### offsetGet()
```php
offsetGet(mixed $offset): Component
```

---

<a name="method-offsetset"></a>

### offsetSet()
```php
offsetSet(mixed $offset, mixed $value): void
```
Don't use this method directly, use the add() method for adding.

---

<a name="method-offsetunset"></a>

### offsetUnset()
```php
offsetUnset(mixed $offset): void
```
Alias of removeById().

---

<a name="method-count"></a>

### count()
```php
count(): int
```

---

<a name="method-getiterator"></a>

### getIterator()
```php
getIterator(): \Iterator
```
Don't use this method directly, it only for foreach syntax support.

---

<a name="method-__debuginfo"></a>

### __debugInfo()
```php
__debugInfo(): void
```