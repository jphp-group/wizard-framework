# UIWindow

- **class** `UIWindow` (`framework\web\ui\UIWindow`) **extends** [`UIContainer`](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UIContainer.md)
- **source** `framework/web/ui/UIWindow.php`

**Description**

Class UIWindow

---

#### Properties

- `->`[`title`](#prop-title) : `string`
- `->`[`centered`](#prop-centered) : `bool`
- `->`[`closable`](#prop-closable) : `bool`
- `->`[`resizable`](#prop-resizable) : `bool`
- `->`[`showType`](#prop-showtype) : `string` - _popup, window, dialog, kiosk_
- `->`[`footer`](#prop-footer) : [`UINode`](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UINode.md)
- *See also in the parent class* [UIContainer](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UIContainer.md).

---

#### Methods

- `->`[`__construct()`](#method-__construct) - _UIWindow constructor._
- `->`[`uiSchemaClassName()`](#method-uischemaclassname)
- `->`[`uiSchemaEvents()`](#method-uischemaevents)
- `->`[`getTitle()`](#method-gettitle)
- `->`[`setTitle()`](#method-settitle)
- `->`[`isCentered()`](#method-iscentered)
- `->`[`setCentered()`](#method-setcentered)
- `->`[`isClosable()`](#method-isclosable)
- `->`[`setClosable()`](#method-setclosable)
- `->`[`getFooter()`](#method-getfooter)
- `->`[`setFooter()`](#method-setfooter)
- `->`[`isResizable()`](#method-isresizable)
- `->`[`setResizable()`](#method-setresizable)
- `->`[`getShowType()`](#method-getshowtype)
- `->`[`setShowType()`](#method-setshowtype)
- `->`[`innerNodes()`](#method-innernodes)
- `->`[`show()`](#method-show)
- `->`[`free()`](#method-free)
- `->`[`close()`](#method-close) - _Close window._
- `->`[`provideUserInput()`](#method-provideuserinput)
- `->`[`synchronizeUserInput()`](#method-synchronizeuserinput)
- See also in the parent class [UIContainer](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UIContainer.md)

---
# Methods

<a name="method-__construct"></a>

### __construct()
```php
__construct(): void
```
UIWindow constructor.

---

<a name="method-uischemaclassname"></a>

### uiSchemaClassName()
```php
uiSchemaClassName(): string
```

---

<a name="method-uischemaevents"></a>

### uiSchemaEvents()
```php
uiSchemaEvents(): array
```

---

<a name="method-gettitle"></a>

### getTitle()
```php
getTitle(): string
```

---

<a name="method-settitle"></a>

### setTitle()
```php
setTitle(string $title): void
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

<a name="method-isclosable"></a>

### isClosable()
```php
isClosable(): bool
```

---

<a name="method-setclosable"></a>

### setClosable()
```php
setClosable(bool $closable): void
```

---

<a name="method-getfooter"></a>

### getFooter()
```php
getFooter(): framework\web\ui\UINode
```

---

<a name="method-setfooter"></a>

### setFooter()
```php
setFooter([ framework\web\ui\UINode $footer): void
```

---

<a name="method-isresizable"></a>

### isResizable()
```php
isResizable(): bool
```

---

<a name="method-setresizable"></a>

### setResizable()
```php
setResizable(bool $resizable): void
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

<a name="method-innernodes"></a>

### innerNodes()
```php
innerNodes(): array
```

---

<a name="method-show"></a>

### show()
```php
show(): void
```

---

<a name="method-free"></a>

### free()
```php
free(): void
```

---

<a name="method-close"></a>

### close()
```php
close(): void
```
Close window.

---

<a name="method-provideuserinput"></a>

### provideUserInput()
```php
provideUserInput(array $data): void
```

---

<a name="method-synchronizeuserinput"></a>

### synchronizeUserInput()
```php
synchronizeUserInput(array $data): void
```