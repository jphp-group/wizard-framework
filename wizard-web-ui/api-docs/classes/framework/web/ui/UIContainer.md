# UIContainer

- **class** `UIContainer` (`framework\web\ui\UIContainer`) **extends** [`UINode`](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UINode.md)
- **source** `framework/web/ui/UIContainer.php`

**Child Classes**

> [UIAnchorPane](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UIAnchorPane.md), [UIHBox](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UIHBox.md), [UIListView](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UIListView.md), [UIVBox](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UIVBox.md), [UIWindow](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UIWindow.md)

---

#### Properties

- `->`[`horAlign`](#prop-horalign) : `string`
- `->`[`verAlign`](#prop-veralign) : `string`
- `->`[`children`](#prop-children) : `array`
- *See also in the parent class* [UINode](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UINode.md).

---

#### Methods

- `->`[`__construct()`](#method-__construct) - _UIContainer constructor._
- `->`[`uiSchemaClassName()`](#method-uischemaclassname)
- `->`[`uiSchema()`](#method-uischema)
- `->`[`add()`](#method-add)
- `->`[`remove()`](#method-remove)
- `->`[`clear()`](#method-clear) - _Remove All children._
- `->`[`getChildren()`](#method-getchildren)
- `->`[`getHorAlign()`](#method-gethoralign)
- `->`[`setHorAlign()`](#method-sethoralign)
- `->`[`getVerAlign()`](#method-getveralign)
- `->`[`setVerAlign()`](#method-setveralign)
- `->`[`getAlign()`](#method-getalign)
- `->`[`setAlign()`](#method-setalign)
- `->`[`innerNodes()`](#method-innernodes)
- `->`[`connectToUI()`](#method-connecttoui)
- See also in the parent class [UINode](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UINode.md)

---
# Methods

<a name="method-__construct"></a>

### __construct()
```php
__construct(UINode[] $children): void
```
UIContainer constructor.

---

<a name="method-uischemaclassname"></a>

### uiSchemaClassName()
```php
uiSchemaClassName(): string
```

---

<a name="method-uischema"></a>

### uiSchema()
```php
uiSchema(): array
```

---

<a name="method-add"></a>

### add()
```php
add(framework\web\ui\UINode $node): void
```

---

<a name="method-remove"></a>

### remove()
```php
remove(framework\web\ui\UINode $node): bool
```

---

<a name="method-clear"></a>

### clear()
```php
clear(): void
```
Remove All children.

---

<a name="method-getchildren"></a>

### getChildren()
```php
getChildren(): UINode[]
```

---

<a name="method-gethoralign"></a>

### getHorAlign()
```php
getHorAlign(): string
```

---

<a name="method-sethoralign"></a>

### setHorAlign()
```php
setHorAlign(string $horAlign): void
```

---

<a name="method-getveralign"></a>

### getVerAlign()
```php
getVerAlign(): string
```

---

<a name="method-setveralign"></a>

### setVerAlign()
```php
setVerAlign(string $verAlign): void
```

---

<a name="method-getalign"></a>

### getAlign()
```php
getAlign(): array
```

---

<a name="method-setalign"></a>

### setAlign()
```php
setAlign(array $value): void
```

---

<a name="method-innernodes"></a>

### innerNodes()
```php
innerNodes(): array
```

---

<a name="method-connecttoui"></a>

### connectToUI()
```php
connectToUI([ framework\web\UI $ui): void
```