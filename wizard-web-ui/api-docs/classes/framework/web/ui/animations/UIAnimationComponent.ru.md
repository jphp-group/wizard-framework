# UIAnimationComponent

- **класс** `UIAnimationComponent` (`framework\web\ui\animations\UIAnimationComponent`) **унаследован от** `Component` (`framework\core\Component`)
- **исходники** `framework/web/ui/animations/UIAnimationComponent.php`

**Классы наследники**

> [UICSSAnimation](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/animations/UICSSAnimation.ru.md)

**Описание**

Class UIAnimation

---

#### Свойства

- `->`[`idStyle`](#prop-idstyle) : `string`
- `->`[`delay`](#prop-delay) : `mixed`
- `->`[`duration`](#prop-duration) : `mixed`
- `->`[`when`](#prop-when) : `string` - _render, hover, click_
- `->`[`loop`](#prop-loop) : `bool`
- `->`[`reverseAnimated`](#prop-reverseanimated) : `bool`
- `->`[`ownerBindIds`](#prop-ownerbindids) : `array`
- `->`[`onAnimate`](#prop-onanimate) : `EventSignal`
- `->`[`onReverseAnimate`](#prop-onreverseanimate) : `EventSignal`
- `->`[`onInitialize`](#prop-oninitialize) : `EventSignal`

---

#### Методы

- `->`[`__construct()`](#method-__construct) - _UIAnimation constructor._
- `->`[`animate()`](#method-animate)
- `->`[`reverseAnimate()`](#method-reverseanimate)
- `->`[`handleAddTo()`](#method-handleaddto)
- `->`[`handleRemoveFrom()`](#method-handleremovefrom)
- `->`[`getDuration()`](#method-getduration)
- `->`[`setDuration()`](#method-setduration)
- `->`[`getDelay()`](#method-getdelay)
- `->`[`setDelay()`](#method-setdelay)
- `->`[`getWhen()`](#method-getwhen)
- `->`[`setWhen()`](#method-setwhen)
- `->`[`isLoop()`](#method-isloop)
- `->`[`setLoop()`](#method-setloop)
- `->`[`isReverseAnimated()`](#method-isreverseanimated)
- `->`[`setReverseAnimated()`](#method-setreverseanimated)

---
# Методы

<a name="method-__construct"></a>

### __construct()
```php
__construct(mixed $duration, mixed $delay, string $when): void
```
UIAnimation constructor.

---

<a name="method-animate"></a>

### animate()
```php
animate(framework\web\ui\UINode $node): mixed
```

---

<a name="method-reverseanimate"></a>

### reverseAnimate()
```php
reverseAnimate(framework\web\ui\UINode $node): void
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

<a name="method-getduration"></a>

### getDuration()
```php
getDuration(): mixed
```

---

<a name="method-setduration"></a>

### setDuration()
```php
setDuration(mixed $duration): void
```

---

<a name="method-getdelay"></a>

### getDelay()
```php
getDelay(): mixed
```

---

<a name="method-setdelay"></a>

### setDelay()
```php
setDelay(mixed $delay): void
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

---

<a name="method-isloop"></a>

### isLoop()
```php
isLoop(): bool
```

---

<a name="method-setloop"></a>

### setLoop()
```php
setLoop(bool $loop): void
```

---

<a name="method-isreverseanimated"></a>

### isReverseAnimated()
```php
isReverseAnimated(): bool
```

---

<a name="method-setreverseanimated"></a>

### setReverseAnimated()
```php
setReverseAnimated(bool $reverseAnimated): void
```