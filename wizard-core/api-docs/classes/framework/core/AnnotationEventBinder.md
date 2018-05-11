# AnnotationEventBinder

- **class** `AnnotationEventBinder` (`framework\core\AnnotationEventBinder`)
- **source** `framework/core/AnnotationEventBinder.php`

**Description**

Class AnnotationEventBinder

---

#### Properties

- `->`[`context`](#prop-context) : `object`
- `->`[`handler`](#prop-handler) : `object`
- `->`[`lookup`](#prop-lookup) : `callable`

---

#### Methods

- `->`[`__construct()`](#method-__construct) - _AnnotationEventBinder constructor._
- `->`[`lookup()`](#method-lookup)
- `->`[`tryBindMethod()`](#method-trybindmethod)
- `->`[`loadBinds()`](#method-loadbinds)

---
# Methods

<a name="method-__construct"></a>

### __construct()
```php
__construct(framework\core\Component $context, object $handler, [ callable|null $lookup): void
```
AnnotationEventBinder constructor.

---

<a name="method-lookup"></a>

### lookup()
```php
lookup(string $id): framework\core\Component
```

---

<a name="method-trybindmethod"></a>

### tryBindMethod()
```php
tryBindMethod(ReflectionMethod $method, object $context): void
```

---

<a name="method-loadbinds"></a>

### loadBinds()
```php
loadBinds(): void
```