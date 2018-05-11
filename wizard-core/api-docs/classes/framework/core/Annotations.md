# Annotations

- **class** `Annotations` (`framework\core\Annotations`)
- **source** `framework/core/Annotations.php`

**Description**

Class Annotations

---

#### Static Methods

- `Annotations ::`[`getOfClass()`](#method-getofclass)
- `Annotations ::`[`getOfMethod()`](#method-getofmethod)
- `Annotations ::`[`get()`](#method-get)
- `Annotations ::`[`parseMethod()`](#method-parsemethod)
- `Annotations ::`[`parseClass()`](#method-parseclass)
- `Annotations ::`[`parse()`](#method-parse)

---

#### Methods

- `->`[`__construct()`](#method-__construct)

---
# Static Methods

<a name="method-getofclass"></a>

### getOfClass()
```php
Annotations::getOfClass(string $annotationName, ReflectionClass $reflection, null $default): mixed
```

---

<a name="method-getofmethod"></a>

### getOfMethod()
```php
Annotations::getOfMethod(string $annotationName, ReflectionFunctionAbstract $reflection, null $default): mixed
```

---

<a name="method-get"></a>

### get()
```php
Annotations::get(string $annotationName, string $comment, mixed $default): mixed
```

---

<a name="method-parsemethod"></a>

### parseMethod()
```php
Annotations::parseMethod(ReflectionFunctionAbstract $reflection): array
```

---

<a name="method-parseclass"></a>

### parseClass()
```php
Annotations::parseClass(ReflectionClass $reflection): array
```

---

<a name="method-parse"></a>

### parse()
```php
Annotations::parse(string $comment, callable|null $callback): array
```

---
# Methods

<a name="method-__construct"></a>

### __construct()
```php
__construct(): void
```