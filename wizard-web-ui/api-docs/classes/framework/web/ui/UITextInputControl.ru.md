# UITextInputControl

- **класс** `UITextInputControl` (`framework\web\ui\UITextInputControl`) **унаследован от** [`UINode`](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UINode.ru.md)
- **исходники** `framework/web/ui/UITextInputControl.php`

**Классы наследники**

> [UIPasswordField](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UIPasswordField.ru.md), [UITextArea](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UITextArea.ru.md), [UITextField](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UITextField.ru.md)

---

#### Свойства

- `->`[`placeholder`](#prop-placeholder) : `string`
- `->`[`editable`](#prop-editable) : `bool`
- `->`[`textAlign`](#prop-textalign) : `string`
- `->`[`text`](#prop-text) : `string`
- `->`[`font`](#prop-font) : [`UIFont`](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UIFont.ru.md)
- *См. также в родительском классе* [UINode](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UINode.ru.md).

---

#### Методы

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
- См. также в родительском классе [UINode](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UINode.ru.md)

---
# Методы

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