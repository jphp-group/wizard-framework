# AbstractUISession

- **класс** `AbstractUISession` (`framework\web\AbstractUISession`)
- **исходники** `framework/web/AbstractUISession.php`

**Описание**

Class AbstractSocketSession

---

#### Методы

- `->`[`sendText()`](#method-sendtext)
- `->`[`isOpen()`](#method-isopen)
- `->`[`close()`](#method-close)
- `->`[`disconnect()`](#method-disconnect)

---
# Методы

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

---

<a name="method-close"></a>

### close()
```php
close(int $status, string|null $reason): void
```

---

<a name="method-disconnect"></a>

### disconnect()
```php
disconnect(): void
```