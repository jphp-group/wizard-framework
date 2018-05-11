# UILoader

- **class** `UILoader` (`framework\web\UILoader`) **extends** `Component` (`framework\core\Component`)
- **source** `framework/web/UILoader.php`

**Description**

Class UILoader

---

#### Properties

- `->`[`node`](#prop-node) : [`UINode`](https://github.com/jphp-group/wizard-framework/blob/master/wizard-web-ui/api-docs/classes/framework/web/ui/UINode.md)
- `->`[`subNodes`](#prop-subnodes) : `UINode[]`
- `->`[`components`](#prop-components) : `array`
- `->`[`componentLoader`](#prop-componentloader) : `ComponentLoader`

---

#### Methods

- `->`[`__construct()`](#method-__construct)
- `->`[`findComponent()`](#method-findcomponent)
- `->`[`extend()`](#method-extend)
- `->`[`import()`](#method-import)
- `->`[`_load()`](#method-_load)
- `->`[`getNode()`](#method-getnode)
- `->`[`getNodesById()`](#method-getnodesbyid)
- `->`[`load()`](#method-load)
- `->`[`loadFromStream()`](#method-loadfromstream)

---
# Methods

<a name="method-__construct"></a>

### __construct()
```php
__construct(): void
```

---

<a name="method-findcomponent"></a>

### findComponent()
```php
findComponent(mixed $name): void
```

---

<a name="method-extend"></a>

### extend()
```php
extend(array $data, string $componentName): array
```

---

<a name="method-import"></a>

### import()
```php
import(array $data): void
```

---

<a name="method-_load"></a>

### _load()
```php
_load(array $data): framework\web\ui\UINode
```

---

<a name="method-getnode"></a>

### getNode()
```php
getNode(): framework\web\ui\UINode
```

---

<a name="method-getnodesbyid"></a>

### getNodesById()
```php
getNodesById(): UINode[]
```

---

<a name="method-load"></a>

### load()
```php
load(array $data): void
```

---

<a name="method-loadfromstream"></a>

### loadFromStream()
```php
loadFromStream(php\io\Stream $stream, [ null|string $schemaKey, string $format): mixed
```