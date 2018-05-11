# UIEffectComponent

- **класс** `UIEffectComponent` (`framework\web\ui\effects\UIEffectComponent`) **унаследован от** `Component` (`framework\core\Component`)
- **исходники** `framework/web/ui/effects/UIEffectComponent.php`

**Классы наследники**

> [UIFilterEffectComponent](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/effects/UIFilterEffectComponent.ru.md), [UIShadowEffect](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/effects/UIShadowEffect.ru.md)

**Описание**

Class UIEffect

---

#### Свойства

- `->`[`when`](#prop-when) : `string`
- `->`[`ownerBindIds`](#prop-ownerbindids) : `array`

---

#### Методы

- `->`[`apply()`](#method-apply)
- `->`[`reset()`](#method-reset)
- `->`[`handleAddTo()`](#method-handleaddto)
- `->`[`handleRemoveFrom()`](#method-handleremovefrom)
- `->`[`getWhen()`](#method-getwhen)
- `->`[`setWhen()`](#method-setwhen)

---
# Методы

<a name="method-apply"></a>

### apply()
```php
apply(framework\web\ui\UINode $node): void
```

---

<a name="method-reset"></a>

### reset()
```php
reset(framework\web\ui\UINode $node): void
```

---

<a name="method-handleaddto"></a>

### handleAddTo()
```php
handleAddTo(framework\core\Event $e): void
```

---

<a name="method-handleremovefrom"></a>

### handleRemoveFrom()
```php
handleRemoveFrom(framework\core\Event $e): void
```

---

<a name="method-getwhen"></a>

### getWhen()
```php
getWhen(): string
```

---

<a name="method-setwhen"></a>

### setWhen()
```php
setWhen(string $when): void
```