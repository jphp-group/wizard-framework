# AbstractWebAppModule

- **класс** `AbstractWebAppModule` (`framework\app\AbstractWebAppModule`) **унаследован от** `Component` (`framework\core\Component`)
- **исходники** `framework/app/AbstractWebAppModule.php`

---

#### Свойства

- `->`[`uiClasses`](#prop-uiclasses) : `array`
- `->`[`dnextJsFile`](#prop-dnextjsfile) : `string`
- `->`[`dnextCssFile`](#prop-dnextcssfile) : `string`
- `->`[`dnextResources`](#prop-dnextresources) : `array`

---

#### Методы

- `->`[`__construct()`](#method-__construct) - _AbstractWebAppModule constructor._
- `->`[`setupResources()`](#method-setupresources) - _Enable rich user interface._
- `->`[`addUI()`](#method-addui)

---
# Методы

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