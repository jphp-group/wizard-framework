# SocketMessage

- **класс** `SocketMessage` (`framework\web\SocketMessage`)
- **исходники** `framework/web/SocketMessage.php`

**Описание**

Class SocketMessage

---

#### Свойства

- `->`[`id`](#prop-id) : `string`
- `->`[`type`](#prop-type) : `string`
- `->`[`data`](#prop-data) : `array`

---

#### Методы

- `->`[`__construct()`](#method-__construct) - _SocketMessage constructor._
- `->`[`getId()`](#method-getid)
- `->`[`getType()`](#method-gettype)
- `->`[`getData()`](#method-getdata)
- `->`[`reply()`](#method-reply)

---
# Методы

<a name="method-__construct"></a>

### __construct()
```php
__construct(array $rawData): void
```
SocketMessage constructor.

---

<a name="method-getid"></a>

### getId()
```php
getId(): string
```

---

<a name="method-gettype"></a>

### getType()
```php
getType(): string
```

---

<a name="method-getdata"></a>

### getData()
```php
getData(): array
```

---

<a name="method-reply"></a>

### reply()
```php
reply(framework\web\AbstractUISession $session, array $data, callable $callback): void
```