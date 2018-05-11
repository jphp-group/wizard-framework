# UIButton

- **class** `UIButton` (`framework\web\ui\UIButton`) **extends** [`UILabeled`](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UILabeled.md)
- **source** `framework/web/ui/UIButton.php`

**Child Classes**

> [UIToggleButton](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UIToggleButton.md)

**Description**

Class UXButton

---

#### Properties

- `->`[`kind`](#prop-kind) : `string` - _default, primary, success, info, warning, danger, link_
- `->`[`outline`](#prop-outline) : `bool`
- `->`[`onAction`](#prop-onaction) : `EventSignal`
- *See also in the parent class* [UILabeled](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UILabeled.md).

---

#### Methods

- `->`[`uiSchemaClassName()`](#method-uischemaclassname)
- `->`[`isOutline()`](#method-isoutline)
- `->`[`setOutline()`](#method-setoutline)
- `->`[`getKind()`](#method-getkind)
- `->`[`setKind()`](#method-setkind)
- `->`[`addEventLink()`](#method-addeventlink)
- See also in the parent class [UILabeled](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UILabeled.md)

---
# Methods

<a name="method-uischemaclassname"></a>

### uiSchemaClassName()
```php
uiSchemaClassName(): string
```

---

<a name="method-isoutline"></a>

### isOutline()
```php
isOutline(): bool
```

---

<a name="method-setoutline"></a>

### setOutline()
```php
setOutline(bool $outline): void
```

---

<a name="method-getkind"></a>

### getKind()
```php
getKind(): string
```

---

<a name="method-setkind"></a>

### setKind()
```php
setKind(string $kind): void
```

---

<a name="method-addeventlink"></a>

### addEventLink()
```php
addEventLink(mixed $eventType): void
```