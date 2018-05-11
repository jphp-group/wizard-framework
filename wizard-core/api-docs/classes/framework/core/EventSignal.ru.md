# EventSignal

- **класс** `EventSignal` (`framework\core\EventSignal`)
- **исходники** `framework/core/EventSignal.php`

**Описание**

Class EventSignal

---

#### Свойства

- `->`[`component`](#prop-component) : [`Component`](https://github.com/jphp-group/wizard-framework/blob/master/wizard-core/api-docs/classes/framework/core/Component.ru.md)
- `->`[`event`](#prop-event) : `string`

---

#### Методы

- `->`[`__construct()`](#method-__construct) - _EventSignal constructor._
- `->`[`set()`](#method-set)
- `->`[`add()`](#method-add)
- `->`[`addOnce()`](#method-addonce)
- `->`[`trigger()`](#method-trigger)

---
# Методы

<a name="method-__construct"></a>

### __construct()
```php
__construct(framework\core\Component $component, string $event): void
```
EventSignal constructor.

---

<a name="method-set"></a>

### set()
```php
set(callable $callback, string $group): void
```

---

<a name="method-add"></a>

### add()
```php
add(callable $callback): string
```

---

<a name="method-addonce"></a>

### addOnce()
```php
addOnce(callable $callback): string
```

---

<a name="method-trigger"></a>

### trigger()
```php
trigger(array|null $data, string|null $group): void
```