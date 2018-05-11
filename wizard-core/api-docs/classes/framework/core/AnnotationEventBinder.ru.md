# AnnotationEventBinder

- **класс** `AnnotationEventBinder` (`framework\core\AnnotationEventBinder`)
- **исходники** `framework/core/AnnotationEventBinder.php`

**Описание**

Class AnnotationEventBinder

---

#### Свойства

- `->`[`context`](#prop-context) : `object`
- `->`[`handler`](#prop-handler) : `object`
- `->`[`lookup`](#prop-lookup) : `callable`

---

#### Методы

- `->`[`__construct()`](#method-__construct) - _AnnotationEventBinder constructor._
- `->`[`lookup()`](#method-lookup)
- `->`[`tryBindMethod()`](#method-trybindmethod)
- `->`[`loadBinds()`](#method-loadbinds)

---
# Методы

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