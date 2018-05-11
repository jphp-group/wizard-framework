# WebSocketUISession

- **class** `WebSocketUISession` (`framework\app\web\WebSocketUISession`) **extends** `AbstractUISession` (`framework\web\AbstractUISession`)
- **source** `framework/app/web/WebSocketUISession.php`

**Description**

Class WebSocketUISession

---

#### Properties

- `->`[`session`](#prop-session) : `WebSocketSession`

---

#### Methods

- `->`[`__construct()`](#method-__construct) - _WebSocketUISession constructor._
- `->`[`sendText()`](#method-sendtext)
- `->`[`isOpen()`](#method-isopen)

---
# Methods

<a name="method-__construct"></a>

### __construct()
```php
__construct(php\http\WebSocketSession $session): void
```
WebSocketUISession constructor.

---

<a name="method-sendtext"></a>

### sendText()
```php
sendText(string $text, [ callable|null $callback): void
```

---

<a name="method-isopen"></a>

### isOpen()
```php
isOpen(): bool
```