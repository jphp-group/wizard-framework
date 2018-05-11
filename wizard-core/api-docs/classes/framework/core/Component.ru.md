# Component

- **класс** `Component` (`framework\core\Component`)
- **исходники** `framework/core/Component.php`

**Классы наследники**

> [Application](https://github.com/jphp-group/wizard-framework/blob/master/wizard-core/api-docs/classes/framework/core/Application.ru.md), [Behaviour](https://github.com/jphp-group/wizard-framework/blob/master/wizard-core/api-docs/classes/framework/core/Behaviour.ru.md), [Module](https://github.com/jphp-group/wizard-framework/blob/master/wizard-core/api-docs/classes/framework/core/Module.ru.md)

**Описание**

Class Component

---

#### Свойства

- `->`[`id`](#prop-id) : `string`
- `->`[`components`](#prop-components) : `Component[]`
- `->`[`eventHandlers`](#prop-eventhandlers) : `array`
- `->`[`data`](#prop-data) : `array`
- `->`[`eventSignals`](#prop-eventsignals) : `EventSignal[]`
- `->`[`owner`](#prop-owner) : [`Component`](https://github.com/jphp-group/wizard-framework/blob/master/wizard-core/api-docs/classes/framework/core/Component.ru.md)

---

#### Методы

- `->`[`__construct()`](#method-__construct) - _Component constructor._
- `->`[`getOwner()`](#method-getowner)
- `->`[`__setOwner()`](#method-__setowner)
- `->`[`getComponents()`](#method-getcomponents)
- `->`[`getProperties()`](#method-getproperties)
- `->`[`setProperties()`](#method-setproperties)
- `->`[`data()`](#method-data)
- `->`[`on()`](#method-on)
- `->`[`bind()`](#method-bind)
- `->`[`off()`](#method-off)
- `->`[`trigger()`](#method-trigger)
- `->`[`getId()`](#method-getid)
- `->`[`setId()`](#method-setid)
- `->`[`loadBinds()`](#method-loadbinds) - _Load event binds._
- `->`[`free()`](#method-free) - _Remove this component from owner._
- `->`[`__get()`](#method-__get)
- `->`[`__set()`](#method-__set)
- `->`[`__debugInfo()`](#method-__debuginfo)

---
# Методы

<a name="method-__construct"></a>

### __construct()
```php
__construct(): void
```
Component constructor.

---

<a name="method-getowner"></a>

### getOwner()
```php
getOwner(): framework\core\Component
```

---

<a name="method-__setowner"></a>

### __setOwner()
```php
__setOwner([ framework\core\Component $component): void
```

---

<a name="method-getcomponents"></a>

### getComponents()
```php
getComponents(): framework\core\Components
```

---

<a name="method-getproperties"></a>

### getProperties()
```php
getProperties(): array
```

---

<a name="method-setproperties"></a>

### setProperties()
```php
setProperties(array $properties): void
```

---

<a name="method-data"></a>

### data()
```php
data(string $name, null|mixed $value): mixed
```

---

<a name="method-on"></a>

### on()
```php
on(string $eventType, callable $handler, string $group): void
```

---

<a name="method-bind"></a>

### bind()
```php
bind(string $eventType, callable $handler): string
```

---

<a name="method-off"></a>

### off()
```php
off(string $eventType, [ null|string $group): void
```

---

<a name="method-trigger"></a>

### trigger()
```php
trigger(framework\core\Event $e, [ null|string $group): void
```

---

<a name="method-getid"></a>

### getId()
```php
getId(): string
```

---

<a name="method-setid"></a>

### setId()
```php
setId(string $id): void
```

---

<a name="method-loadbinds"></a>

### loadBinds()
```php
loadBinds(): void
```
Load event binds.

---

<a name="method-free"></a>

### free()
```php
free(): void
```
Remove this component from owner.

---

<a name="method-__get"></a>

### __get()
```php
__get(string $name): bool|EventSignal|mixed
```

---

<a name="method-__set"></a>

### __set()
```php
__set(string $name, mixed $value): bool|EventSignal|mixed
```

---

<a name="method-__debuginfo"></a>

### __debugInfo()
```php
__debugInfo(): array
```