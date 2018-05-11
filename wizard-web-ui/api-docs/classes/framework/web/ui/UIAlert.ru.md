# UIAlert

- **класс** `UIAlert` (`framework\web\ui\UIAlert`) **унаследован от** [`UIForm`](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/UIForm.ru.md)
- **исходники** `framework/web/ui/UIAlert.php`

**Описание**

Class UIAlert

---

#### Свойства

- `->`[`type`](#prop-type) : `string` - _info, success, warning, error, confirm_
- `->`[`text`](#prop-text) : `string`
- `->`[`textAlign`](#prop-textalign) : `string`
- `->`[`buttons`](#prop-buttons) : `array`
- `->`[`preFormatted`](#prop-preformatted) : `bool`
- `->`[`kinds`](#prop-kinds) : `array`
- *См. также в родительском классе* [UIForm](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/UIForm.ru.md).

---

#### Методы

- `->`[`__construct()`](#method-__construct) - _UIAlert constructor._
- `->`[`isPreFormatted()`](#method-ispreformatted)
- `->`[`setPreFormatted()`](#method-setpreformatted)
- `->`[`getType()`](#method-gettype)
- `->`[`setType()`](#method-settype)
- `->`[`getText()`](#method-gettext)
- `->`[`setText()`](#method-settext)
- `->`[`getTextAlign()`](#method-gettextalign)
- `->`[`setTextAlign()`](#method-settextalign)
- `->`[`getButtons()`](#method-getbuttons)
- `->`[`getTextType()`](#method-gettexttype)
- `->`[`setTextType()`](#method-settexttype)
- `->`[`setButtons()`](#method-setbuttons)
- См. также в родительском классе [UIForm](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/UIForm.ru.md)

---
# Методы

<a name="method-__construct"></a>

### __construct()
```php
__construct(string $type, array $buttons): void
```
UIAlert constructor.

---

<a name="method-ispreformatted"></a>

### isPreFormatted()
```php
isPreFormatted(): bool
```

---

<a name="method-setpreformatted"></a>

### setPreFormatted()
```php
setPreFormatted(bool $preFormatted): void
```

---

<a name="method-gettype"></a>

### getType()
```php
getType(): string
```

---

<a name="method-settype"></a>

### setType()
```php
setType(string $type): void
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

<a name="method-getbuttons"></a>

### getButtons()
```php
getButtons(): array
```

---

<a name="method-gettexttype"></a>

### getTextType()
```php
getTextType(): string
```

---

<a name="method-settexttype"></a>

### setTextType()
```php
setTextType(string $textType): void
```

---

<a name="method-setbuttons"></a>

### setButtons()
```php
setButtons(array $buttons): void
```