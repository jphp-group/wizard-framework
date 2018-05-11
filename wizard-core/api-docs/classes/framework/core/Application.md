# Application

- **class** `Application` (`framework\core\Application`) **extends** [`Component`](https://github.com/jphp-group/wizard-framework/blob/master/wizard-core/api-docs/classes/framework/core/Component.md)
- **source** `framework/core/Application.php`

**Description**

Class Application

---

#### Properties

- `->`[`instance`](#prop-instance) : `mixed`
- `->`[`settings`](#prop-settings) : [`Settings`](https://github.com/jphp-group/wizard-framework/blob/master/wizard-core/api-docs/classes/framework/core/Settings.md)
- `->`[`singletons`](#prop-singletons) : `Component[]`
- `->`[`stamp`](#prop-stamp) : `string`
- `->`[`initializeTime`](#prop-initializetime) : `int`
- *See also in the parent class* [Component](https://github.com/jphp-group/wizard-framework/blob/master/wizard-core/api-docs/classes/framework/core/Component.md).

---

#### Static Methods

- `Application ::`[`current()`](#method-current)
- `Application ::`[`isInitialized()`](#method-isinitialized)
- See also in the parent class [Component](https://github.com/jphp-group/wizard-framework/blob/master/wizard-core/api-docs/classes/framework/core/Component.md)

---

#### Methods

- `->`[`__construct()`](#method-__construct) - _Application constructor._
- `->`[`getInitializeTime()`](#method-getinitializetime)
- `->`[`getStamp()`](#method-getstamp)
- `->`[`getSingletonInstance()`](#method-getsingletoninstance)
- `->`[`addSettings()`](#method-addsettings)
- `->`[`getInstance()`](#method-getinstance)
- `->`[`initialize()`](#method-initialize) - _Initialize application._
- `->`[`launch()`](#method-launch)
- `->`[`addModule()`](#method-addmodule)
- See also in the parent class [Component](https://github.com/jphp-group/wizard-framework/blob/master/wizard-core/api-docs/classes/framework/core/Component.md)

---
# Static Methods

<a name="method-current"></a>

### current()
```php
Application::current(): Application
```

---

<a name="method-isinitialized"></a>

### isInitialized()
```php
Application::isInitialized(): bool
```

---
# Methods

<a name="method-__construct"></a>

### __construct()
```php
__construct(): void
```
Application constructor.

---

<a name="method-getinitializetime"></a>

### getInitializeTime()
```php
getInitializeTime(): int
```

---

<a name="method-getstamp"></a>

### getStamp()
```php
getStamp(): string
```

---

<a name="method-getsingletoninstance"></a>

### getSingletonInstance()
```php
getSingletonInstance(string $class): framework\core\Component
```

---

<a name="method-addsettings"></a>

### addSettings()
```php
addSettings(string $path, string $format): void
```

---

<a name="method-getinstance"></a>

### getInstance()
```php
getInstance(string $class): framework\core\Component
```

---

<a name="method-initialize"></a>

### initialize()
```php
initialize(): void
```
Initialize application.

---

<a name="method-launch"></a>

### launch()
```php
launch(): void
```

---

<a name="method-addmodule"></a>

### addModule()
```php
addModule(framework\core\Component $module): void
```