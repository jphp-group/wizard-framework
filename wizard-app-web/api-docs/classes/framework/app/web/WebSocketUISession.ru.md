# WebSocketUISession

- **класс** `WebSocketUISession` (`framework\app\web\WebSocketUISession`) **унаследован от** `AbstractUISession` (`framework\web\AbstractUISession`)
- **исходники** `framework/app/web/WebSocketUISession.php`

**Описание**

Class WebSocketUISession

---

#### Свойства

- `->`[`session`](#prop-session) : `WebSocketSession`

---

#### Методы

- `->`[`__construct()`](#method-__construct) - _WebSocketUISession constructor._
- `->`[`sendText()`](#method-sendtext)
- `->`[`isOpen()`](#method-isopen)

---
# Методы

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