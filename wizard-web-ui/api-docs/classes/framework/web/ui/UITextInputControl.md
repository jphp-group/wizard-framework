# UITextInputControl

- **class** `UITextInputControl` (`framework\web\ui\UITextInputControl`) **extends** [`UINode`](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UINode.md)
- **source** `framework/web/ui/UITextInputControl.php`

**Child Classes**

> [UIPasswordField](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UIPasswordField.md), [UITextArea](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UITextArea.md), [UITextField](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UITextField.md)

---

#### Properties

- `->`[`placeholder`](#prop-placeholder) : `string`
- `->`[`editable`](#prop-editable) : `bool`
- `->`[`textAlign`](#prop-textalign) : `string`
- `->`[`text`](#prop-text) : `string`
- `->`[`font`](#prop-font) : [`UIFont`](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UIFont.md)
- *See also in the parent class* [UINode](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UINode.md).

---

#### Methods

- `->`[`__construct()`](#method-__construct)
- `->`[`getFont()`](#method-getfont)
- `->`[`setFont()`](#method-setfont)
- `->`[`getPlaceholder()`](#method-getplaceholder)
- `->`[`setPlaceholder()`](#method-setplaceholder)
- `->`[`isEditable()`](#method-iseditable)
- `->`[`setEditable()`](#method-seteditable)
- `->`[`getTextAlign()`](#method-gettextalign)
- `->`[`setTextAlign()`](#method-settextalign)
- `->`[`getText()`](#method-gettext)
- `->`[`setText()`](#method-settext)
- `->`[`provideUserInput()`](#method-provideuserinput)
- `->`[`synchronizeUserInput()`](#method-synchronizeuserinput)
- See also in the parent class [UINode](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UINode.md)

---
# Methods

<a name="method-__construct"></a>

### __construct()
```php
__construct(): void
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

<a name="method-getplaceholder"></a>

### getPlaceholder()
```php
getPlaceholder(): string
```

---

<a name="method-setplaceholder"></a>

### setPlaceholder()
```php
setPlaceholder(string $placeholder): void
```

---

<a name="method-iseditable"></a>

### isEditable()
```php
isEditable(): bool
```

---

<a name="method-seteditable"></a>

### setEditable()
```php
setEditable(bool $editable): void
```

---

<a name="method-gettextalign"></a>

### getTextAlign()
```php
getTextAlign(): string
```

---

<a name="method-settextalign"></a>

### setTextAlign()
```php
setTextAlign(string $textAlign): void
```

---

<a name="method-gettext"></a>

### getText()
```php
getText(): string
```

---

<a name="method-settext"></a>

### setText()
```php
setText(string $text): void
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