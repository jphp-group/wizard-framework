# UIShadowEffect

- **класс** `UIShadowEffect` (`framework\web\ui\effects\UIShadowEffect`) **унаследован от** [`UIEffectComponent`](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/effects/UIEffectComponent.ru.md)
- **исходники** `framework/web/ui/effects/UIShadowEffect.php`

**Описание**

Class UIShadowEffect

---

#### Свойства

- `->`[`radius`](#prop-radius) : `float`
- `->`[`color`](#prop-color) : `string`
- `->`[`offset`](#prop-offset) : `array`
- `->`[`inner`](#prop-inner) : `bool`
- `->`[`currentStyle`](#prop-currentstyle) : `string`
- *См. также в родительском классе* [UIEffectComponent](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/effects/UIEffectComponent.ru.md).

---

#### Методы

- `->`[`__construct()`](#method-__construct) - _UIShadowEffect constructor._
- `->`[`getCssProperty()`](#method-getcssproperty)
- `->`[`handleApply()`](#method-handleapply)
- `->`[`handleReset()`](#method-handlereset)
- `->`[`getRadius()`](#method-getradius)
- `->`[`setRadius()`](#method-setradius)
- `->`[`getColor()`](#method-getcolor)
- `->`[`setColor()`](#method-setcolor)
- `->`[`getOffset()`](#method-getoffset)
- `->`[`setOffset()`](#method-setoffset)
- `->`[`isInner()`](#method-isinner)
- `->`[`setInner()`](#method-setinner)
- См. также в родительском классе [UIEffectComponent](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/effects/UIEffectComponent.ru.md)

---
# Методы

<a name="method-__construct"></a>

### __construct()
```php
__construct(float $radius, string $color, array $offset, bool $inner): void
```
UIShadowEffect constructor.

---

<a name="method-getcssproperty"></a>

### getCssProperty()
```php
getCssProperty(): string
```

---

<a name="method-handleapply"></a>

### handleApply()
```php
handleApply(): void
```

---

<a name="method-handlereset"></a>

### handleReset()
```php
handleReset(): void
```

---

<a name="method-getradius"></a>

### getRadius()
```php
getRadius(): float
```

---

<a name="method-setradius"></a>

### setRadius()
```php
setRadius(float $radius): void
```

---

<a name="method-getcolor"></a>

### getColor()
```php
getColor(): string
```

---

<a name="method-setcolor"></a>

### setColor()
```php
setColor(string $color): void
```

---

<a name="method-getoffset"></a>

### getOffset()
```php
getOffset(): array
```

---

<a name="method-setoffset"></a>

### setOffset()
```php
setOffset(array $offset): void
```

---

<a name="method-isinner"></a>

### isInner()
```php
isInner(): bool
```

---

<a name="method-setinner"></a>

### setInner()
```php
setInner(bool $inner): void
```