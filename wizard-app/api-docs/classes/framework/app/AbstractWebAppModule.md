# AbstractWebAppModule

- **class** `AbstractWebAppModule` (`framework\app\AbstractWebAppModule`) **extends** `Component` (`framework\core\Component`)
- **source** `framework/app/AbstractWebAppModule.php`

---

#### Properties

- `->`[`uiClasses`](#prop-uiclasses) : `array`
- `->`[`dnextJsFile`](#prop-dnextjsfile) : `string`
- `->`[`dnextCssFile`](#prop-dnextcssfile) : `string`
- `->`[`dnextResources`](#prop-dnextresources) : `array`

---

#### Methods

- `->`[`__construct()`](#method-__construct) - _AbstractWebAppModule constructor._
- `->`[`setupResources()`](#method-setupresources) - _Enable rich user interface._
- `->`[`addUI()`](#method-addui)

---
# Methods

<a name="method-__construct"></a>

### __construct()
```php
__construct(): void
```
AbstractWebAppModule constructor.

---

<a name="method-setupresources"></a>

### setupResources()
```php
setupResources(string $jsFile, string $cssFile): $this
```
Enable rich user interface.

---

<a name="method-addui"></a>

### addUI()
```php
addUI(string $uiClass): $this
```