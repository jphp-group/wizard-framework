# Event

- **class** `Event` (`framework\core\Event`)
- **source** `framework/core/Event.php`

**Description**

Class Event

---

#### Properties

- `->`[`type`](#prop-type) : `string`
- `->`[`sender`](#prop-sender) : `Component|object`
- `->`[`consumed`](#prop-consumed) : `bool`
- `->`[`context`](#prop-context) : `Component|object`
- `->`[`data`](#prop-data) : `array`

---

#### Methods

- `->`[`__construct()`](#method-__construct) - _Event constructor._
- `->`[`isConsumed()`](#method-isconsumed)
- `->`[`consume()`](#method-consume) - _Consume event._
- `->`[`__get()`](#method-__get)
- `->`[`__isset()`](#method-__isset)

---
# Methods

<a name="method-__construct"></a>

### __construct()
```php
__construct(string $type, object $sender, [ object|null $context, array|null $data ]): void
```
Event constructor.

---

<a name="method-isconsumed"></a>

### isConsumed()
```php
isConsumed(): bool
```

---

<a name="method-consume"></a>

### consume()
```php
consume(): void
```
Consume event.

---

<a name="method-__get"></a>

### __get()
```php
__get(string $name): object|string|array
```

---

<a name="method-__isset"></a>

### __isset()
```php
__isset(mixed $name): void
```