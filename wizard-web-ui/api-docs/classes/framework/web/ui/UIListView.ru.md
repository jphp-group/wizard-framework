# UIListView

- **класс** `UIListView` (`framework\web\ui\UIListView`) **унаследован от** [`UIContainer`](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UIContainer.ru.md)
- **исходники** `framework/web/ui/UIListView.php`

**Описание**

Class UIListView

---

#### Свойства

- `->`[`selected`](#prop-selected) : [`UINode`](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UINode.ru.md)
- `->`[`selectedIndex`](#prop-selectedindex) : `int`
- `->`[`font`](#prop-font) : [`UIFont`](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UIFont.ru.md)
- `->`[`onChange`](#prop-onchange) : `EventSignal`
- `->`[`onAction`](#prop-onaction) : `EventSignal`
- *См. также в родительском классе* [UIContainer](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UIContainer.ru.md).

---

#### Методы

- `->`[`__construct()`](#method-__construct) - _UIListView constructor._
- `->`[`uiSchemaClassName()`](#method-uischemaclassname)
- `->`[`uiSchema()`](#method-uischema)
- `->`[`getFont()`](#method-getfont)
- `->`[`setFont()`](#method-setfont)
- `->`[`getSelected()`](#method-getselected)
- `->`[`setSelected()`](#method-setselected)
- `->`[`getSelectedIndex()`](#method-getselectedindex)
- `->`[`setSelectedIndex()`](#method-setselectedindex)
- `->`[`provideUserInput()`](#method-provideuserinput)
- `->`[`synchronizeUserInput()`](#method-synchronizeuserinput)
- См. также в родительском классе [UIContainer](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UIContainer.ru.md)

---
# Методы

<a name="method-__construct"></a>

### __construct()
```php
__construct(array $children): void
```
UIListView constructor.

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

<a name="method-getfont"></a>

### getFont()
```php
getFont(): framework\web\ui\UIFont
```

---

<a name="method-setfont"></a>

### setFont()
```php
setFont(UIFont|array|string $font): void
```

---

<a name="method-getselected"></a>

### getSelected()
```php
getSelected(): framework\web\ui\UINode
```

---

<a name="method-setselected"></a>

### setSelected()
```php
setSelected([ framework\web\ui\UINode $selected): void
```

---

<a name="method-getselectedindex"></a>

### getSelectedIndex()
```php
getSelectedIndex(): int
```

---

<a name="method-setselectedindex"></a>

### setSelectedIndex()
```php
setSelectedIndex(int $selectedIndex): void
```

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