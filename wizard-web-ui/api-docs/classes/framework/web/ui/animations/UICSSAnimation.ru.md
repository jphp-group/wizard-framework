# UICSSAnimation

- **класс** `UICSSAnimation` (`framework\web\ui\animations\UICSSAnimation`) **унаследован от** [`UIAnimationComponent`](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/animations/UIAnimationComponent.ru.md)
- **исходники** `framework/web/ui/animations/UICSSAnimation.php`

---

#### Свойства

- `->`[`frames`](#prop-frames) : `array`
- `->`[`timer`](#prop-timer) : `Timer`
- *См. также в родительском классе* [UIAnimationComponent](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/animations/UIAnimationComponent.ru.md).

---

#### Методы

- `->`[`__construct()`](#method-__construct) - _UIFadeAnimation constructor._
- `->`[`makeCssAnimation()`](#method-makecssanimation)
- `->`[`handleInitialize()`](#method-handleinitialize)
- `->`[`handleFinalize()`](#method-handlefinalize)
- `->`[`handleAnimate()`](#method-handleanimate)
- `->`[`handleReverseAnimate()`](#method-handlereverseanimate)
- `->`[`getFrames()`](#method-getframes)
- `->`[`setFrames()`](#method-setframes)
- См. также в родительском классе [UIAnimationComponent](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/animations/UIAnimationComponent.ru.md)

---
# Методы

<a name="method-__construct"></a>

### __construct()
```php
__construct(array $frames, string $duration, int $delay, string $when): void
```
UIFadeAnimation constructor.

---

<a name="method-makecssanimation"></a>

### makeCssAnimation()
```php
makeCssAnimation(mixed $name, array $frames, boolean $reverse): void
```

---

<a name="method-handleinitialize"></a>

### handleInitialize()
```php
handleInitialize(): void
```

---

<a name="method-handlefinalize"></a>

### handleFinalize()
```php
handleFinalize(): void
```

---

<a name="method-handleanimate"></a>

### handleAnimate()
```php
handleAnimate(): void
```

---

<a name="method-handlereverseanimate"></a>

### handleReverseAnimate()
```php
handleReverseAnimate(framework\core\Event $e): void
```

---

<a name="method-getframes"></a>

### getFrames()
```php
getFrames(): array
```

---

<a name="method-setframes"></a>

### setFrames()
```php
setFrames(array $frames): void
```