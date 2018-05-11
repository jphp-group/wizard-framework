# AppUI

- **class** `AppUI` (`framework\web\AppUI`) **extends** [`UI`](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/UI.md)
- **source** `framework/web/AppUI.php`

**Description**

Class AppUI

---

#### Properties

- `->`[`currentForm`](#prop-currentform) : [`UIForm`](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/UIForm.md)
- `->`[`forms`](#prop-forms) : `UIForm[]`
- `->`[`urlForms`](#prop-urlforms) : [`UIForm`](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/UIForm.md)
- `->`[`notFoundForm`](#prop-notfoundform) : [`UIForm`](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/UIForm.md)
- `->`[`console`](#prop-console) : [`WebConsole`](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/WebConsole.md)
- *See also in the parent class* [UI](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/UI.md).

---

#### Methods

- `->`[`__construct()`](#method-__construct) - _AppUI constructor._
- `->`[`form()`](#method-form)
- `->`[`registerForm()`](#method-registerform)
- `->`[`registerNotFoundForm()`](#method-registernotfoundform)
- `->`[`getConsole()`](#method-getconsole)
- `->`[`makeView()`](#method-makeview)
- `->`[`getView()`](#method-getview)
- `->`[`getCurrentForm()`](#method-getcurrentform)
- `->`[`setCurrentForm()`](#method-setcurrentform)
- `->`[`renderView()`](#method-renderview)
- `->`[`detectCurrentForm()`](#method-detectcurrentform)
- `->`[`show()`](#method-show)
- `->`[`navigateTo()`](#method-navigateto)
- See also in the parent class [UI](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/UI.md)

---
# Methods

<a name="method-__construct"></a>

### __construct()
```php
__construct(): void
```
AppUI constructor.

---

<a name="method-form"></a>

### form()
```php
form(string $code): framework\web\UIForm
```

---

<a name="method-registerform"></a>

### registerForm()
```php
registerForm(string $code, framework\web\UIForm $form): void
```

---

<a name="method-registernotfoundform"></a>

### registerNotFoundForm()
```php
registerNotFoundForm(string $code, framework\web\UIForm $form): void
```

---

<a name="method-getconsole"></a>

### getConsole()
```php
getConsole(): framework\web\WebConsole
```

---

<a name="method-makeview"></a>

### makeView()
```php
makeView(): framework\web\ui\UINode
```

---

<a name="method-getview"></a>

### getView()
```php
getView(): framework\web\ui\UINode
```

---

<a name="method-getcurrentform"></a>

### getCurrentForm()
```php
getCurrentForm(): framework\web\UIForm
```

---

<a name="method-setcurrentform"></a>

### setCurrentForm()
```php
setCurrentForm([ framework\web\UIForm $currentForm): void
```

---

<a name="method-renderview"></a>

### renderView()
```php
renderView(): void
```

---

<a name="method-detectcurrentform"></a>

### detectCurrentForm()
```php
detectCurrentForm(): void
```

---

<a name="method-show"></a>

### show()
```php
show(mixed $formOrCode, array $args): void
```

---

<a name="method-navigateto"></a>

### navigateTo()
```php
navigateTo(mixed $formOrCode, array $args): void
```