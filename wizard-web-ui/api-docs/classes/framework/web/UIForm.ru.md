# UIForm

- **класс** `UIForm` (`framework\web\UIForm`) **унаследован от** `Component` (`framework\core\Component`)
- **исходники** `framework/web/UIForm.php`

**Классы наследники**

> [UIAlert](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UIAlert.ru.md)

**Описание**

Class UIForm

---

#### Свойства

- `->`[`router`](#prop-router) : `array`
- `->`[`title`](#prop-title) : `string`
- `->`[`layout`](#prop-layout) : [`UINode`](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UINode.ru.md)
- `->`[`nodesById`](#prop-nodesbyid) : [`UINode`](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UINode.ru.md)
- `->`[`connectedUi`](#prop-connectedui) : [`UI`](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/UI.ru.md)
- `->`[`window`](#prop-window) : [`UIWindow`](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UIWindow.ru.md)
- `->`[`footer`](#prop-footer) : [`UINode`](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UINode.ru.md)
- `->`[`closable`](#prop-closable) : `bool`
- `->`[`centered`](#prop-centered) : `bool`
- `->`[`x`](#prop-x) : `int`
- `->`[`y`](#prop-y) : `int`
- `->`[`width`](#prop-width) : `int`
- `->`[`height`](#prop-height) : `int`
- `->`[`showType`](#prop-showtype) : `string` - _popup, window_
- `->`[`onNavigate`](#prop-onnavigate) : `EventSignal`
- `->`[`onShow`](#prop-onshow) : `EventSignal`
- `->`[`initialized`](#prop-initialized) : `bool`

---

#### Методы

- `->`[`__construct()`](#method-__construct) - _UIForm constructor._
- `->`[`loadBinds()`](#method-loadbinds)
- `->`[`loadDescription()`](#method-loaddescription)
- `->`[`fetchWindow()`](#method-fetchwindow)
- `->`[`createWindow()`](#method-createwindow)
- `->`[`open()`](#method-open) - _Open Form in App UI._
- `->`[`show()`](#method-show) - _Show window._
- `->`[`hide()`](#method-hide) - _Hide Window._
- `->`[`getRoutePath()`](#method-getroutepath)
- `->`[`getRoutePaths()`](#method-getroutepaths)
- `->`[`getFrmPath()`](#method-getfrmpath)
- `->`[`getFrmFormat()`](#method-getfrmformat)
- `->`[`loadFrm()`](#method-loadfrm)
- `->`[`getConnectedUI()`](#method-getconnectedui)
- `->`[`isConnectedToUI()`](#method-isconnectedtoui)
- `->`[`connectToUI()`](#method-connecttoui)
- `->`[`disconnectUI()`](#method-disconnectui)
- `->`[`getAppUI()`](#method-getappui)
- `->`[`getLayout()`](#method-getlayout)
- `->`[`getTitle()`](#method-gettitle)
- `->`[`isClosable()`](#method-isclosable)
- `->`[`isCentered()`](#method-iscentered)
- `->`[`setCentered()`](#method-setcentered)
- `->`[`setClosable()`](#method-setclosable)
- `->`[`setTitle()`](#method-settitle)
- `->`[`getX()`](#method-getx)
- `->`[`setX()`](#method-setx)
- `->`[`getY()`](#method-gety)
- `->`[`setY()`](#method-sety)
- `->`[`getPosition()`](#method-getposition)
- `->`[`setPosition()`](#method-setposition)
- `->`[`getWidth()`](#method-getwidth)
- `->`[`setWidth()`](#method-setwidth)
- `->`[`getHeight()`](#method-getheight)
- `->`[`setHeight()`](#method-setheight)
- `->`[`getSize()`](#method-getsize)
- `->`[`setSize()`](#method-setsize)
- `->`[`getShowType()`](#method-getshowtype)
- `->`[`setShowType()`](#method-setshowtype)
- `->`[`__get()`](#method-__get)
- `->`[`__isset()`](#method-__isset)

---
# Методы

<a name="method-__construct"></a>

### __construct()
```php
__construct(): void
```
UIForm constructor.

---

<a name="method-loadbinds"></a>

### loadBinds()
```php
loadBinds(): void
```

---

<a name="method-loaddescription"></a>

### loadDescription()
```php
loadDescription(): void
```

---

<a name="method-fetchwindow"></a>

### fetchWindow()
```php
fetchWindow(): framework\web\ui\UIWindow
```

---

<a name="method-createwindow"></a>

### createWindow()
```php
createWindow(): framework\web\ui\UIWindow
```

---

<a name="method-open"></a>

### open()
```php
open(array $args): void
```
Open Form in App UI.

---

<a name="method-show"></a>

### show()
```php
show(): void
```
Show window.

---

<a name="method-hide"></a>

### hide()
```php
hide(): void
```
Hide Window.

---

<a name="method-getroutepath"></a>

### getRoutePath()
```php
getRoutePath(): string
```

---

<a name="method-getroutepaths"></a>

### getRoutePaths()
```php
getRoutePaths(): array
```

---

<a name="method-getfrmpath"></a>

### getFrmPath()
```php
getFrmPath(): void
```

---

<a name="method-getfrmformat"></a>

### getFrmFormat()
```php
getFrmFormat(): void
```

---

<a name="method-loadfrm"></a>

### loadFrm()
```php
loadFrm(string $frmPath): void
```

---

<a name="method-getconnectedui"></a>

### getConnectedUI()
```php
getConnectedUI(): framework\web\UI
```

---

<a name="method-isconnectedtoui"></a>

### isConnectedToUI()
```php
isConnectedToUI(): bool
```

---

<a name="method-connecttoui"></a>

### connectToUI()
```php
connectToUI(framework\web\UI $ui): void
```

---

<a name="method-disconnectui"></a>

### disconnectUI()
```php
disconnectUI(): void
```

---

<a name="method-getappui"></a>

### getAppUI()
```php
getAppUI(): framework\web\AppUI
```

---

<a name="method-getlayout"></a>

### getLayout()
```php
getLayout(): framework\web\ui\UINode
```

---

<a name="method-gettitle"></a>

### getTitle()
```php
getTitle(): string
```

---

<a name="method-isclosable"></a>

### isClosable()
```php
isClosable(): bool
```

---

<a name="method-iscentered"></a>

### isCentered()
```php
isCentered(): bool
```

---

<a name="method-setcentered"></a>

### setCentered()
```php
setCentered(bool $centered): void
```

---

<a name="method-setclosable"></a>

### setClosable()
```php
setClosable(bool $closable): void
```

---

<a name="method-settitle"></a>

### setTitle()
```php
setTitle(string $title): void
```

---

<a name="method-getx"></a>

### getX()
```php
getX(): int
```

---

<a name="method-setx"></a>

### setX()
```php
setX(int $x): void
```

---

<a name="method-gety"></a>

### getY()
```php
getY(): int
```

---

<a name="method-sety"></a>

### setY()
```php
setY(int $y): void
```

---

<a name="method-getposition"></a>

### getPosition()
```php
getPosition(): array
```

---

<a name="method-setposition"></a>

### setPosition()
```php
setPosition(array $value): void
```

---

<a name="method-getwidth"></a>

### getWidth()
```php
getWidth(): int
```

---

<a name="method-setwidth"></a>

### setWidth()
```php
setWidth(int $width): void
```

---

<a name="method-getheight"></a>

### getHeight()
```php
getHeight(): int
```

---

<a name="method-setheight"></a>

### setHeight()
```php
setHeight(int $height): void
```

---

<a name="method-getsize"></a>

### getSize()
```php
getSize(): array
```

---

<a name="method-setsize"></a>

### setSize()
```php
setSize(array $value): void
```

---

<a name="method-getshowtype"></a>

### getShowType()
```php
getShowType(): string
```

---

<a name="method-setshowtype"></a>

### setShowType()
```php
setShowType(string $showType): void
```

---

<a name="method-__get"></a>

### __get()
```php
__get(string $name): bool|mixed
```

---

<a name="method-__isset"></a>

### __isset()
```php
__isset(string $name): bool
```