# Promise

- **class** `Promise` (`framework\core\Promise`)
- **source** `framework/core/Promise.php`

**Description**

Class Promise

---

#### Properties

- `->`[`state`](#prop-state) : `int`
- `->`[`value`](#prop-value) : `mixed`
- `->`[`subscribers`](#prop-subscribers) : `array`

---

#### Static Methods

- `Promise ::`[`resolve()`](#method-resolve)
- `Promise ::`[`reject()`](#method-reject)
- `Promise ::`[`race()`](#method-race)
- `Promise ::`[`all()`](#method-all)

---

#### Methods

- `->`[`__construct()`](#method-__construct) - _Promise constructor._
- `->`[`makeFulfill()`](#method-makefulfill)
- `->`[`makeReject()`](#method-makereject)
- `->`[`invokeCallback()`](#method-invokecallback)
- `->`[`then()`](#method-then)
- `->`[`catch()`](#method-catch)
- `->`[`wait()`](#method-wait) - _Stops execution until this promise is resolved._

---
# Static Methods

<a name="method-resolve"></a>

### resolve()
```php
Promise::resolve(mixed $result): framework\core\Promise
```

---

<a name="method-reject"></a>

### reject()
```php
Promise::reject(Throwable $error): framework\core\Promise
```

---

<a name="method-race"></a>

### race()
```php
Promise::race(Promise[]|iterable $promises): framework\core\Promise
```

---

<a name="method-all"></a>

### all()
```php
Promise::all(Promise[]|iterable $promises): framework\core\Promise
```

---
# Methods

<a name="method-__construct"></a>

### __construct()
```php
__construct(callable $executor): void
```
Promise constructor.

---

<a name="method-makefulfill"></a>

### makeFulfill()
```php
makeFulfill(mixed $result): void
```

---

<a name="method-makereject"></a>

### makeReject()
```php
makeReject(Throwable $error): void
```

---

<a name="method-invokecallback"></a>

### invokeCallback()
```php
invokeCallback(framework\core\Promise $subPromise, callable $callBack): void
```

---

<a name="method-then"></a>

### then()
```php
then(callable $onFulfilled, callable $onRejected): framework\core\Promise
```

---

<a name="method-catch"></a>

### catch()
```php
catch([ callable|null $onRejected): framework\core\Promise
```

---

<a name="method-wait"></a>

### wait()
```php
wait(): mixed
```
Stops execution until this promise is resolved.