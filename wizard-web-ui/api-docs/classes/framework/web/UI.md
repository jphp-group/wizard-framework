# UI

- **class** `UI` (`framework\web\UI`) **extends** `Component` (`framework\core\Component`)
- **source** `framework/web/UI.php`

**Child Classes**

> [AppUI](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/AppUI.md)

**Description**

Class UI

---

#### Properties

- `->`[`view`](#prop-view) : `mixed`
- `->`[`location`](#prop-location) : `array`
- `->`[`windows`](#prop-windows) : `UIWindow[]`
- `->`[`callbacks`](#prop-callbacks) : `callable[]`
- `->`[`socket`](#prop-socket) : [`UISocket`](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/UISocket.md)
- `->`[`current`](#prop-current) : `ThreadLocal`
- `->`[`alertFunction`](#prop-alertfunction) : `callable`

---

#### Static Methods

- `UI ::`[`checkAvailable()`](#method-checkavailable) - _Setup UI for the current thread._
- `UI ::`[`setup()`](#method-setup)
- `UI ::`[`currentRequired()`](#method-currentrequired)
- `UI ::`[`current()`](#method-current)

---

#### Methods

- `->`[`__construct()`](#method-__construct) - _UI constructor._
- `->`[`makeView()`](#method-makeview)
- `->`[`getRoutePath()`](#method-getroutepath)
- `->`[`getRequestUrl()`](#method-getrequesturl)
- `->`[`getHash()`](#method-gethash)
- `->`[`setHash()`](#method-sethash)
- `->`[`getFullRequestUrl()`](#method-getfullrequesturl)
- `->`[`linkSocket()`](#method-linksocket)
- `->`[`findWindow()`](#method-findwindow)
- `->`[`createCssStyle()`](#method-createcssstyle)
- `->`[`destroyCssStyle()`](#method-destroycssstyle)
- `->`[`addWindow()`](#method-addwindow)
- `->`[`removeWindow()`](#method-removewindow)
- `->`[`setAlertFunction()`](#method-setalertfunction)
- `->`[`getView()`](#method-getview)
- `->`[`getUISchema()`](#method-getuischema)
- `->`[`parseValue()`](#method-parsevalue)
- `->`[`prepareValue()`](#method-preparevalue)
- `->`[`findNodeByUuidGlobally()`](#method-findnodebyuuidglobally)
- `->`[`findNodeByUuid()`](#method-findnodebyuuid)
- `->`[`onMessage()`](#method-onmessage)
- `->`[`makeHtmlView()`](#method-makehtmlview)
- `->`[`executeScript()`](#method-executescript)
- `->`[`sendMessage()`](#method-sendmessage)
- `->`[`renderView()`](#method-renderview) - _Render View._
- `->`[`alert()`](#method-alert)
- `->`[`changeNodeProperty()`](#method-changenodeproperty)
- `->`[`callNodeMethod()`](#method-callnodemethod)
- `->`[`addEventLink()`](#method-addeventlink)

---
# Static Methods

<a name="method-checkavailable"></a>

### checkAvailable()
```php
UI::checkAvailable(): void
```
Setup UI for the current thread.

---

<a name="method-setup"></a>

### setup()
```php
UI::setup([ framework\web\UI $ui): void
```

---

<a name="method-currentrequired"></a>

### currentRequired()
```php
UI::currentRequired(): framework\web\UI
```

---

<a name="method-current"></a>

### current()
```php
UI::current(): framework\web\UI
```

---
# Methods

<a name="method-__construct"></a>

### __construct()
```php
__construct([ framework\web\UISocket $socket): void
```
UI constructor.

---

<a name="method-makeview"></a>

### makeView()
```php
makeView(): framework\web\ui\UINode
```

---

<a name="method-getroutepath"></a>

### getRoutePath()
```php
getRoutePath(): string
```

---

<a name="method-getrequesturl"></a>

### getRequestUrl()
```php
getRequestUrl(): string
```

---

<a name="method-gethash"></a>

### getHash()
```php
getHash(): string
```

---

<a name="method-sethash"></a>

### setHash()
```php
setHash(string $hash): void
```

---

<a name="method-getfullrequesturl"></a>

### getFullRequestUrl()
```php
getFullRequestUrl(): null|string
```

---

<a name="method-linksocket"></a>

### linkSocket()
```php
linkSocket(framework\web\UISocket $socket): void
```

---

<a name="method-findwindow"></a>

### findWindow()
```php
findWindow(string $uuid): framework\web\ui\UIWindow
```

---

<a name="method-createcssstyle"></a>

### createCssStyle()
```php
createCssStyle(string $style, string|null $idStyle, [ callable|null $callback): string
```

---

<a name="method-destroycssstyle"></a>

### destroyCssStyle()
```php
destroyCssStyle(string $idStyle, [ callable|null $callback): void
```

---

<a name="method-addwindow"></a>

### addWindow()
```php
addWindow(framework\web\ui\UIWindow $window): void
```

---

<a name="method-removewindow"></a>

### removeWindow()
```php
removeWindow(framework\web\ui\UIWindow $window): void
```

---

<a name="method-setalertfunction"></a>

### setAlertFunction()
```php
setAlertFunction([ callable|null $function): void
```

---

<a name="method-getview"></a>

### getView()
```php
getView(): framework\web\ui\UINode
```

---

<a name="method-getuischema"></a>

### getUISchema()
```php
getUISchema(): array
```

---

<a name="method-parsevalue"></a>

### parseValue()
```php
parseValue(mixed $value): array|mixed
```

---

<a name="method-preparevalue"></a>

### prepareValue()
```php
prepareValue(mixed $value): array|mixed
```

---

<a name="method-findnodebyuuidglobally"></a>

### findNodeByUuidGlobally()
```php
findNodeByUuidGlobally(string $uuid): framework\web\ui\UINode
```

---

<a name="method-findnodebyuuid"></a>

### findNodeByUuid()
```php
findNodeByUuid(string $uuid, [ framework\web\ui\UINode $view): framework\web\ui\UINode
```

---

<a name="method-onmessage"></a>

### onMessage()
```php
onMessage(framework\web\UIMessageEvent $e): void
```

---

<a name="method-makehtmlview"></a>

### makeHtmlView()
```php
makeHtmlView(string $path, string $jsAppDispatcher, array $resources, array $args): string
```

---

<a name="method-executescript"></a>

### executeScript()
```php
executeScript(string $jsScript, callable|null $callback): void
```

---

<a name="method-sendmessage"></a>

### sendMessage()
```php
sendMessage(string $type, array $message, callable|null $callback): void
```

---

<a name="method-renderview"></a>

### renderView()
```php
renderView(): void
```
Render View.

---

<a name="method-alert"></a>

### alert()
```php
alert(string $message, array $options): void
```

---

<a name="method-changenodeproperty"></a>

### changeNodeProperty()
```php
changeNodeProperty(framework\web\ui\UINode $node, string $property, mixed $value): void
```

---

<a name="method-callnodemethod"></a>

### callNodeMethod()
```php
callNodeMethod(framework\web\ui\UINode $node, string $method, array $args): void
```

---

<a name="method-addeventlink"></a>

### addEventLink()
```php
addEventLink(framework\web\ui\UINode $node, string $eventType): void
```