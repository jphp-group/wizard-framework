# Annotations

- **класс** `Annotations` (`framework\core\Annotations`)
- **исходники** `framework/core/Annotations.php`

**Описание**

Class Annotations

---

#### Статичные Методы

- `Annotations ::`[`getOfClass()`](#method-getofclass)
- `Annotations ::`[`getOfMethod()`](#method-getofmethod)
- `Annotations ::`[`get()`](#method-get)
- `Annotations ::`[`parseMethod()`](#method-parsemethod)
- `Annotations ::`[`parseClass()`](#method-parseclass)
- `Annotations ::`[`parse()`](#method-parse)

---

#### Методы

- `->`[`__construct()`](#method-__construct)

---
# Статичные Методы

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
# Методы

<a name="method-__construct"></a>

### __construct()
```php
__construct(): void
```