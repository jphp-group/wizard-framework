# UIFont

- **класс** `UIFont` (`framework\web\ui\UIFont`) **унаследован от** `Component` (`framework\core\Component`)
- **исходники** `framework/web/ui/UIFont.php`

**Описание**

Class UIFont

---

#### Свойства

- `->`[`name`](#prop-name) : `string`
- `->`[`size`](#prop-size) : `mixed`
- `->`[`bold`](#prop-bold) : `bool`
- `->`[`underline`](#prop-underline) : `bool`
- `->`[`linethrough`](#prop-linethrough) : `bool`
- `->`[`italic`](#prop-italic) : `bool`
- `->`[`onChange`](#prop-onchange) : `callable`

---

#### Статичные Методы

- `UIFont ::`[`wrapper()`](#method-wrapper)
- `UIFont ::`[`fetch()`](#method-fetch)

---

#### Методы

- `->`[`getName()`](#method-getname)
- `->`[`setName()`](#method-setname)
- `->`[`getSize()`](#method-getsize)
- `->`[`setSize()`](#method-setsize)
- `->`[`isBold()`](#method-isbold)
- `->`[`setBold()`](#method-setbold)
- `->`[`isUnderline()`](#method-isunderline)
- `->`[`setUnderline()`](#method-setunderline)
- `->`[`isLinethrough()`](#method-islinethrough)
- `->`[`setLinethrough()`](#method-setlinethrough)
- `->`[`isItalic()`](#method-isitalic)
- `->`[`setItalic()`](#method-setitalic)
- `->`[`uiSchema()`](#method-uischema)

---
# Статичные Методы

<a name="method-wrapper"></a>

### wrapper()
```php
UIFont::wrapper(framework\web\ui\UINode $node, string $property, framework\web\ui\UIFont $value): UIFont
```

---

<a name="method-fetch"></a>

### fetch()
```php
UIFont::fetch(string|array|UIFont $value): UIFont
```

---
# Методы

<a name="method-getname"></a>

### getName()
```php
getName(): string
```

---

<a name="method-setname"></a>

### setName()
```php
setName(string $name): void
```

---

<a name="method-getsize"></a>

### getSize()
```php
getSize(): mixed
```

---

<a name="method-setsize"></a>

### setSize()
```php
setSize(mixed $size): void
```

---

<a name="method-isbold"></a>

### isBold()
```php
isBold(): bool
```

---

<a name="method-setbold"></a>

### setBold()
```php
setBold(bool $bold): void
```

---

<a name="method-isunderline"></a>

### isUnderline()
```php
isUnderline(): bool
```

---

<a name="method-setunderline"></a>

### setUnderline()
```php
setUnderline(bool $underline): void
```

---

<a name="method-islinethrough"></a>

### isLinethrough()
```php
isLinethrough(): bool
```

---

<a name="method-setlinethrough"></a>

### setLinethrough()
```php
setLinethrough(bool $linethrough): void
```

---

<a name="method-isitalic"></a>

### isItalic()
```php
isItalic(): bool
```

---

<a name="method-setitalic"></a>

### setItalic()
```php
setItalic(bool $italic): void
```

---

<a name="method-uischema"></a>

### uiSchema()
```php
uiSchema(): array
```