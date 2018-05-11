# UINode

- **class** `UINode` (`framework\web\ui\UINode`) **extends** `Component` (`framework\core\Component`)
- **source** `framework/web/ui/UINode.php`

**Child Classes**

> [UIContainer](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UIContainer.md), [UIIcon](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UIIcon.md), [UIImageView](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UIImageView.md), [UILabeled](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UILabeled.md), [UIProgressBar](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UIProgressBar.md), [UISelectControl](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UISelectControl.md), [UITextInputControl](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UITextInputControl.md)

---

#### Properties

- `->`[`uuid`](#prop-uuid) : `string`
- `->`[`style`](#prop-style) : `string`
- `->`[`classes`](#prop-classes) : `array`
- `->`[`width`](#prop-width) : `string|int`
- `->`[`height`](#prop-height) : `string|int`
- `->`[`x`](#prop-x) : `int`
- `->`[`y`](#prop-y) : `int`
- `->`[`visible`](#prop-visible) : `bool`
- `->`[`enabled`](#prop-enabled) : `bool`
- `->`[`selectionEnabled`](#prop-selectionenabled) : `bool`
- `->`[`tooltip`](#prop-tooltip) : `string`
- `->`[`tooltipOptions`](#prop-tooltipoptions) : `array`
- `->`[`padding`](#prop-padding) : `array`
- `->`[`cursor`](#prop-cursor) : `string`
- `->`[`connectedUi`](#prop-connectedui) : [`UI`](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/UI.md)
- `->`[`parent`](#prop-parent) : [`UIContainer`](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UIContainer.md)
- `->`[`onClick`](#prop-onclick) : `EventSignal`
- `->`[`onMouseDown`](#prop-onmousedown) : `EventSignal`
- `->`[`onMouseUp`](#prop-onmouseup) : `EventSignal`
- `->`[`onKeyDown`](#prop-onkeydown) : `EventSignal`
- `->`[`onKeyUp`](#prop-onkeyup) : `EventSignal`
- `->`[`onKeyPress`](#prop-onkeypress) : `EventSignal`

---

#### Methods

- `->`[`uiSchemaClassName()`](#method-uischemaclassname)
- `->`[`uiSchemaEvents()`](#method-uischemaevents)
- `->`[`__construct()`](#method-__construct) - _UXNode constructor._
- `->`[`uiSchema()`](#method-uischema)
- `->`[`__set()`](#method-__set)
- `->`[`getUuid()`](#method-getuuid)
- `->`[`setUuid()`](#method-setuuid)
- `->`[`getStyle()`](#method-getstyle)
- `->`[`setStyle()`](#method-setstyle)
- `->`[`getClasses()`](#method-getclasses)
- `->`[`setClasses()`](#method-setclasses)
- `->`[`getWidth()`](#method-getwidth)
- `->`[`setWidth()`](#method-setwidth)
- `->`[`getHeight()`](#method-getheight)
- `->`[`setHeight()`](#method-setheight)
- `->`[`getSize()`](#method-getsize)
- `->`[`setSize()`](#method-setsize)
- `->`[`getX()`](#method-getx)
- `->`[`setX()`](#method-setx)
- `->`[`getY()`](#method-gety)
- `->`[`setY()`](#method-sety)
- `->`[`getPosition()`](#method-getposition)
- `->`[`setPosition()`](#method-setposition)
- `->`[`isVisible()`](#method-isvisible)
- `->`[`setVisible()`](#method-setvisible)
- `->`[`isEnabled()`](#method-isenabled)
- `->`[`setEnabled()`](#method-setenabled)
- `->`[`isSelectionEnabled()`](#method-isselectionenabled)
- `->`[`setSelectionEnabled()`](#method-setselectionenabled)
- `->`[`getOpacity()`](#method-getopacity)
- `->`[`setOpacity()`](#method-setopacity)
- `->`[`getPadding()`](#method-getpadding)
- `->`[`setPadding()`](#method-setpadding)
- `->`[`getTooltip()`](#method-gettooltip)
- `->`[`setTooltip()`](#method-settooltip)
- `->`[`getTooltipOptions()`](#method-gettooltipoptions)
- `->`[`setTooltipOptions()`](#method-settooltipoptions)
- `->`[`getCursor()`](#method-getcursor)
- `->`[`setCursor()`](#method-setcursor)
- `->`[`getParent()`](#method-getparent)
- `->`[`__setParent()`](#method-__setparent)
- `->`[`getConnectedUI()`](#method-getconnectedui)
- `->`[`isConnectedToUI()`](#method-isconnectedtoui)
- `->`[`connectToUI()`](#method-connecttoui)
- `->`[`disconnectUI()`](#method-disconnectui)
- `->`[`toFront()`](#method-tofront)
- `->`[`toBack()`](#method-toback)
- `->`[`hide()`](#method-hide)
- `->`[`show()`](#method-show)
- `->`[`toggle()`](#method-toggle)
- `->`[`addCssStyle()`](#method-addcssstyle)
- `->`[`removeCssStyle()`](#method-removecssstyle)
- `->`[`css()`](#method-css)
- `->`[`fadeTo()`](#method-fadeto)
- `->`[`fadeIn()`](#method-fadein)
- `->`[`fadeOut()`](#method-fadeout)
- `->`[`animate()`](#method-animate)
- `->`[`stopAllAnimate()`](#method-stopallanimate)
- `->`[`stopAnimate()`](#method-stopanimate)
- `->`[`free()`](#method-free)
- `->`[`isFree()`](#method-isfree)
- `->`[`innerNodes()`](#method-innernodes)
- `->`[`on()`](#method-on)
- `->`[`addEventLink()`](#method-addeventlink)
- `->`[`callRemoteMethod()`](#method-callremotemethod)
- `->`[`changeRemoteProperty()`](#method-changeremoteproperty)
- `->`[`provideUserInput()`](#method-provideuserinput)
- `->`[`provideUserInputProperties()`](#method-provideuserinputproperties)
- `->`[`synchronizeUserInput()`](#method-synchronizeuserinput)
- `->`[`__clone()`](#method-__clone)

---
# Methods

<a name="method-uischemaclassname"></a>

### uiSchemaClassName()
```php
uiSchemaClassName(): string
```

---

<a name="method-uischemaevents"></a>

### uiSchemaEvents()
```php
uiSchemaEvents(): array
```

---

<a name="method-__construct"></a>

### __construct()
```php
__construct(): void
```
UXNode constructor.

---

<a name="method-uischema"></a>

### uiSchema()
```php
uiSchema(): array
```

---

<a name="method-__set"></a>

### __set()
```php
__set(string $name, mixed $value): void
```

---

<a name="method-getuuid"></a>

### getUuid()
```php
getUuid(): string
```

---

<a name="method-setuuid"></a>

### setUuid()
```php
setUuid(string $uuid): void
```

---

<a name="method-getstyle"></a>

### getStyle()
```php
getStyle(): string
```

---

<a name="method-setstyle"></a>

### setStyle()
```php
setStyle(string $style): void
```

---

<a name="method-getclasses"></a>

### getClasses()
```php
getClasses(): array|string
```

---

<a name="method-setclasses"></a>

### setClasses()
```php
setClasses(array|string $classes): void
```

---

<a name="method-getwidth"></a>

### getWidth()
```php
getWidth(): int|string
```

---

<a name="method-setwidth"></a>

### setWidth()
```php
setWidth(int|string $width): void
```

---

<a name="method-getheight"></a>

### getHeight()
```php
getHeight(): int|string
```

---

<a name="method-setheight"></a>

### setHeight()
```php
setHeight(int|string $height): void
```

---

<a name="method-getsize"></a>

### getSize()
```php
getSize(): array
```

---

<a name="method-setsize"></a>

### setSize()
```php
setSize(array $size): void
```

---

<a name="method-getx"></a>

### getX()
```php
getX(): int
```

---

<a name="method-setx"></a>

### setX()
```php
setX(int $x): void
```

---

<a name="method-gety"></a>

### getY()
```php
getY(): int
```

---

<a name="method-sety"></a>

### setY()
```php
setY(int $y): void
```

---

<a name="method-getposition"></a>

### getPosition()
```php
getPosition(): array
```

---

<a name="method-setposition"></a>

### setPosition()
```php
setPosition(int[] $position): void
```

---

<a name="method-isvisible"></a>

### isVisible()
```php
isVisible(): bool
```

---

<a name="method-setvisible"></a>

### setVisible()
```php
setVisible(bool $visible): void
```

---

<a name="method-isenabled"></a>

### isEnabled()
```php
isEnabled(): bool
```

---

<a name="method-setenabled"></a>

### setEnabled()
```php
setEnabled(bool $enabled): void
```

---

<a name="method-isselectionenabled"></a>

### isSelectionEnabled()
```php
isSelectionEnabled(): bool
```

---

<a name="method-setselectionenabled"></a>

### setSelectionEnabled()
```php
setSelectionEnabled(bool $selectionEnabled): void
```

---

<a name="method-getopacity"></a>

### getOpacity()
```php
getOpacity(): float
```

---

<a name="method-setopacity"></a>

### setOpacity()
```php
setOpacity([ float $opacity): void
```

---

<a name="method-getpadding"></a>

### getPadding()
```php
getPadding(): array
```

---

<a name="method-setpadding"></a>

### setPadding()
```php
setPadding(array|mixed $padding): void
```

---

<a name="method-gettooltip"></a>

### getTooltip()
```php
getTooltip(): string|UINode
```

---

<a name="method-settooltip"></a>

### setTooltip()
```php
setTooltip(string|UINode $tooltip): void
```

---

<a name="method-gettooltipoptions"></a>

### getTooltipOptions()
```php
getTooltipOptions(): array
```

---

<a name="method-settooltipoptions"></a>

### setTooltipOptions()
```php
setTooltipOptions(array $tooltipOptions): void
```

---

<a name="method-getcursor"></a>

### getCursor()
```php
getCursor(): string
```

---

<a name="method-setcursor"></a>

### setCursor()
```php
setCursor(string $cursor): void
```

---

<a name="method-getparent"></a>

### getParent()
```php
getParent(): framework\web\ui\UIContainer
```

---

<a name="method-__setparent"></a>

### __setParent()
```php
__setParent([ framework\web\ui\UIContainer $parent): void
```

---

<a name="method-getconnectedui"></a>

### getConnectedUI()
```php
getConnectedUI(): framework\web\UI
```

---

<a name="method-isconnectedtoui"></a>

### isConnectedToUI()
```php
isConnectedToUI(): bool
```

---

<a name="method-connecttoui"></a>

### connectToUI()
```php
connectToUI([ framework\web\UI $ui): void
```

---

<a name="method-disconnectui"></a>

### disconnectUI()
```php
disconnectUI(): void
```

---

<a name="method-tofront"></a>

### toFront()
```php
toFront(): void
```

---

<a name="method-toback"></a>

### toBack()
```php
toBack(): void
```

---

<a name="method-hide"></a>

### hide()
```php
hide(): void
```

---

<a name="method-show"></a>

### show()
```php
show(): void
```

---

<a name="method-toggle"></a>

### toggle()
```php
toggle(): void
```

---

<a name="method-addcssstyle"></a>

### addCssStyle()
```php
addCssStyle(string|array $style, [ null|string $idStyle, callable|null $callback ]): null|string
```

---

<a name="method-removecssstyle"></a>

### removeCssStyle()
```php
removeCssStyle(string $idStyle): string
```

---

<a name="method-css"></a>

### css()
```php
css(array|string $style): null|string
```

---

<a name="method-fadeto"></a>

### fadeTo()
```php
fadeTo(int|string $duration, float $opacity, callable|null $complete, string $queue): void
```

---

<a name="method-fadein"></a>

### fadeIn()
```php
fadeIn(int|string $duration, callable|null $complete, string $queue): void
```

---

<a name="method-fadeout"></a>

### fadeOut()
```php
fadeOut(int|string $duration, callable|null $complete, string $queue): void
```

---

<a name="method-animate"></a>

### animate()
```php
animate(array $properties, array $options, [ null|string $queue): void
```

---

<a name="method-stopallanimate"></a>

### stopAllAnimate()
```php
stopAllAnimate(boolean $clearQueue, boolean $jumpToEnd, [ callable $callback): void
```

---

<a name="method-stopanimate"></a>

### stopAnimate()
```php
stopAnimate(bool $clearQueue, bool $jumpToEnd, null|string $queue, [ callable|null $callback): void
```

---

<a name="method-free"></a>

### free()
```php
free(): void
```

---

<a name="method-isfree"></a>

### isFree()
```php
isFree(): void
```

---

<a name="method-innernodes"></a>

### innerNodes()
```php
innerNodes(): array
```

---

<a name="method-on"></a>

### on()
```php
on(string $eventType, callable $handler, string $group): void
```

---

<a name="method-addeventlink"></a>

### addEventLink()
```php
addEventLink(mixed $eventType): void
```

---

<a name="method-callremotemethod"></a>

### callRemoteMethod()
```php
callRemoteMethod(string $method, array $args, bool $waitConnect): void
```

---

<a name="method-changeremoteproperty"></a>

### changeRemoteProperty()
```php
changeRemoteProperty(string $property, mixed $value): void
```

---

<a name="method-provideuserinput"></a>

### provideUserInput()
```php
provideUserInput(array $data): void
```

---

<a name="method-provideuserinputproperties"></a>

### provideUserInputProperties()
```php
provideUserInputProperties(array $props, array $data): void
```

---

<a name="method-synchronizeuserinput"></a>

### synchronizeUserInput()
```php
synchronizeUserInput(array $data): void
```

---

<a name="method-__clone"></a>

### __clone()
```php
__clone(): void
```