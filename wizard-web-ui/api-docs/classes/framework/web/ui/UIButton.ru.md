# UIButton

- **класс** `UIButton` (`framework\web\ui\UIButton`) **унаследован от** [`UILabeled`](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UILabeled.ru.md)
- **исходники** `framework/web/ui/UIButton.php`

**Классы наследники**

> [UIToggleButton](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UIToggleButton.ru.md)

**Описание**

Class UXButton

---

#### Свойства

- `->`[`kind`](#prop-kind) : `string` - _default, primary, success, info, warning, danger, link_
- `->`[`outline`](#prop-outline) : `bool`
- `->`[`onAction`](#prop-onaction) : `EventSignal`
- *См. также в родительском классе* [UILabeled](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UILabeled.ru.md).

---

#### Методы

- `->`[`uiSchemaClassName()`](#method-uischemaclassname)
- `->`[`isOutline()`](#method-isoutline)
- `->`[`setOutline()`](#method-setoutline)
- `->`[`getKind()`](#method-getkind)
- `->`[`setKind()`](#method-setkind)
- `->`[`addEventLink()`](#method-addeventlink)
- См. также в родительском классе [UILabeled](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UILabeled.ru.md)

---
# Методы

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