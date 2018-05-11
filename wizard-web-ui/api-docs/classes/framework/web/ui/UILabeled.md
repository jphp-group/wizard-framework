# UILabeled

- **class** `UILabeled` (`framework\web\ui\UILabeled`) **extends** [`UINode`](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UINode.md)
- **source** `framework/web/ui/UILabeled.php`

**Child Classes**

> [UIButton](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UIButton.md), [UICheckbox](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UICheckbox.md), [UIHyperlink](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UIHyperlink.md), [UILabel](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UILabel.md)

---

#### Properties

- `->`[`text`](#prop-text) : `string`
- `->`[`textType`](#prop-texttype) : `string` - _text or html_
- `->`[`textPreFormatted`](#prop-textpreformatted) : `bool`
- `->`[`font`](#prop-font) : [`UIFont`](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UIFont.md)
- `->`[`graphic`](#prop-graphic) : `UINode|null`
- `->`[`graphicTextGap`](#prop-graphictextgap) : `int`
- `->`[`contentDisplay`](#prop-contentdisplay) : `string`
- `->`[`align`](#prop-align) : `array`
- `->`[`horAlign`](#prop-horalign) : `string`
- `->`[`verAlign`](#prop-veralign) : `string`
- *See also in the parent class* [UINode](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UINode.md).

---

#### Methods

- `->`[`__construct()`](#method-__construct) - _UXButton constructor._
- `->`[`getText()`](#method-gettext)
- `->`[`setText()`](#method-settext)
- `->`[`getTextType()`](#method-gettexttype)
- `->`[`setTextType()`](#method-settexttype)
- `->`[`isTextPreFormatted()`](#method-istextpreformatted)
- `->`[`setTextPreFormatted()`](#method-settextpreformatted)
- `->`[`getGraphic()`](#method-getgraphic)
- `->`[`setGraphic()`](#method-setgraphic)
- `->`[`getContentDisplay()`](#method-getcontentdisplay)
- `->`[`setContentDisplay()`](#method-setcontentdisplay)
- `->`[`getFont()`](#method-getfont)
- `->`[`getGraphicTextGap()`](#method-getgraphictextgap)
- `->`[`setGraphicTextGap()`](#method-setgraphictextgap)
- `->`[`setFont()`](#method-setfont)
- `->`[`getAlign()`](#method-getalign)
- `->`[`setAlign()`](#method-setalign)
- `->`[`getHorAlign()`](#method-gethoralign)
- `->`[`setHorAlign()`](#method-sethoralign)
- `->`[`getVerAlign()`](#method-getveralign)
- `->`[`setVerAlign()`](#method-setveralign)
- `->`[`innerNodes()`](#method-innernodes)
- See also in the parent class [UINode](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UINode.md)

---
# Methods

<a name="method-__construct"></a>

### __construct()
```php
__construct(string|null $text, framework\web\ui\UINode $graphic): void
```
UXButton constructor.

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

<a name="method-istextpreformatted"></a>

### isTextPreFormatted()
```php
isTextPreFormatted(): bool
```

---

<a name="method-settextpreformatted"></a>

### setTextPreFormatted()
```php
setTextPreFormatted(bool $textPreFormatted): void
```

---

<a name="method-getgraphic"></a>

### getGraphic()
```php
getGraphic(): framework\web\ui\UINode
```

---

<a name="method-setgraphic"></a>

### setGraphic()
```php
setGraphic([ framework\web\ui\UINode $graphic): void
```

---

<a name="method-getcontentdisplay"></a>

### getContentDisplay()
```php
getContentDisplay(): string
```

---

<a name="method-setcontentdisplay"></a>

### setContentDisplay()
```php
setContentDisplay(string $contentDisplay): void
```

---

<a name="method-getfont"></a>

### getFont()
```php
getFont(): framework\web\ui\UIFont
```

---

<a name="method-getgraphictextgap"></a>

### getGraphicTextGap()
```php
getGraphicTextGap(): int
```

---

<a name="method-setgraphictextgap"></a>

### setGraphicTextGap()
```php
setGraphicTextGap(int $graphicTextGap): void
```

---

<a name="method-setfont"></a>

### setFont()
```php
setFont(UIFont|array|string $font): void
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
setAlign(array $align): void
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

<a name="method-innernodes"></a>

### innerNodes()
```php
innerNodes(): array
```