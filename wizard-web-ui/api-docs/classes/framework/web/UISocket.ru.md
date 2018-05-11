# UISocket

- **класс** `UISocket` (`framework\web\UISocket`) **унаследован от** `Component` (`framework\core\Component`)
- **исходники** `framework/web/UISocket.php`

**Описание**

Class UISocket

---

#### Свойства

- `->`[`sessions`](#prop-sessions) : `AbstractUISession[][]`
- `->`[`activeSessionUuid`](#prop-activesessionuuid) : `string[]`
- `->`[`excludeActivated`](#prop-excludeactivated) : `bool`

---

#### Методы

- `->`[`__construct()`](#method-__construct) - _UISocket constructor._
- `->`[`isExcludeActivated()`](#method-isexcludeactivated)
- `->`[`setExcludeActivated()`](#method-setexcludeactivated)
- `->`[`initialize()`](#method-initialize)
- `->`[`activate()`](#method-activate)
- `->`[`receiveMessage()`](#method-receivemessage)
- `->`[`onMessage()`](#method-onmessage)
- `->`[`sendText()`](#method-sendtext)
- `->`[`shutdown()`](#method-shutdown) - _Shutdown all ws sessions._

---
# Методы

<a name="method-__construct"></a>

### __construct()
```php
__construct(): void
```
UISocket constructor.

---

<a name="method-isexcludeactivated"></a>

### isExcludeActivated()
```php
isExcludeActivated(): bool
```

---

<a name="method-setexcludeactivated"></a>

### setExcludeActivated()
```php
setExcludeActivated(bool $excludeActivated): void
```

---

<a name="method-initialize"></a>

### initialize()
```php
initialize(string $uiClass, framework\web\AbstractUISession $session, array $message): void
```

---

<a name="method-activate"></a>

### activate()
```php
activate(string $uiClass, array $message): void
```

---

<a name="method-receivemessage"></a>

### receiveMessage()
```php
receiveMessage(string $uiClass, framework\web\SocketMessage $message): void
```

---

<a name="method-onmessage"></a>

### onMessage()
```php
onMessage(string $uiClass, callable $handler): void
```

---

<a name="method-sendtext"></a>

### sendText()
```php
sendText(string $uiClass, string $type, array $data, callable|null $callback): void
```

---

<a name="method-shutdown"></a>

### shutdown()
```php
shutdown(): void
```
Shutdown all ws sessions.