# UICheckbox

- **class** `UICheckbox` (`framework\web\ui\UICheckbox`) **extends** [`UILabeled`](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UILabeled.md)
- **source** `framework/web/ui/UICheckbox.php`

**Child Classes**

> [UISwitch](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UISwitch.md)

**Description**

Class UXCheckbox

---

#### Properties

- `->`[`selected`](#prop-selected) : `bool`
- `->`[`onAction`](#prop-onaction) : `EventSignal`
- `->`[`onChange`](#prop-onchange) : `EventSignal`
- *See also in the parent class* [UILabeled](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UILabeled.md).

---

#### Methods

- `->`[`uiSchemaClassName()`](#method-uischemaclassname)
- `->`[`isSelected()`](#method-isselected)
- `->`[`setSelected()`](#method-setselected)
- `->`[`provideUserInput()`](#method-provideuserinput)
- `->`[`synchronizeUserInput()`](#method-synchronizeuserinput)
- See also in the parent class [UILabeled](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UILabeled.md)

---
# Methods

<a name="method-uischemaclassname"></a>

### uiSchemaClassName()
```php
uiSchemaClassName(): string
```

---

<a name="method-isselected"></a>

### isSelected()
```php
isSelected(): bool
```

---

<a name="method-setselected"></a>

### setSelected()
```php
setSelected(bool $selected): void
```

---

<a name="method-provideuserinput"></a>

### provideUserInput()
```php
provideUserInput(array $data): void
```

---

<a name="method-synchronizeuserinput"></a>

### synchronizeUserInput()
```php
synchronizeUserInput(array $data): void
```