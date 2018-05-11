# AppUI

- **класс** `AppUI` (`framework\web\AppUI`) **унаследован от** [`UI`](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/UI.ru.md)
- **исходники** `framework/web/AppUI.php`

**Описание**

Class AppUI

---

#### Свойства

- `->`[`currentForm`](#prop-currentform) : [`UIForm`](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/UIForm.ru.md)
- `->`[`forms`](#prop-forms) : `UIForm[]`
- `->`[`urlForms`](#prop-urlforms) : [`UIForm`](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/UIForm.ru.md)
- `->`[`notFoundForm`](#prop-notfoundform) : [`UIForm`](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/UIForm.ru.md)
- `->`[`console`](#prop-console) : [`WebConsole`](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/WebConsole.ru.md)
- *См. также в родительском классе* [UI](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/UI.ru.md).

---

#### Методы

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
- См. также в родительском классе [UI](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/UI.ru.md)

---
# Методы

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