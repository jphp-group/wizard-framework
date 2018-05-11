# WebApplication

- **класс** `WebApplication` (`framework\web\WebApplication`) **унаследован от** `Application` (`framework\core\Application`)
- **исходники** `framework/web/WebApplication.php`

**Описание**

Class WebApplication

---

#### Свойства

- `->`[`server`](#prop-server) : `HttpServer`
- `->`[`request`](#prop-request) : `ThreadLocal`
- `->`[`response`](#prop-response) : `ThreadLocal`
- `->`[`isolatedSessionInstances`](#prop-isolatedsessioninstances) : `array`
- `->`[`globalSessionInstances`](#prop-globalsessioninstances) : `array`

---

#### Методы

- `->`[`shutdown()`](#method-shutdown) - _Shutdown._
- `->`[`initialize()`](#method-initialize)
- `->`[`getInstance()`](#method-getinstance)
- `->`[`server()`](#method-server)
- `->`[`request()`](#method-request)
- `->`[`response()`](#method-response)
- `->`[`setupRequestAndResponse()`](#method-setuprequestandresponse)
- `->`[`getWebServerPort()`](#method-getwebserverport)
- `->`[`getWebServerHost()`](#method-getwebserverhost)
- `->`[`isWebServerPortAvailable()`](#method-iswebserverportavailable)
- `->`[`launch()`](#method-launch)
- `->`[`addController()`](#method-addcontroller)

---
# Методы

<a name="method-shutdown"></a>

### shutdown()
```php
shutdown(): void
```
Shutdown.

---

<a name="method-initialize"></a>

### initialize()
```php
initialize(): void
```

---

<a name="method-getinstance"></a>

### getInstance()
```php
getInstance(string $class): framework\core\Component
```

---

<a name="method-server"></a>

### server()
```php
server(): php\http\HttpServer
```

---

<a name="method-request"></a>

### request()
```php
request(): php\http\HttpServerRequest
```

---

<a name="method-response"></a>

### response()
```php
response(): php\http\HttpServerResponse
```

---

<a name="method-setuprequestandresponse"></a>

### setupRequestAndResponse()
```php
setupRequestAndResponse(php\http\HttpServerRequest $request, php\http\HttpServerResponse $response): void
```

---

<a name="method-getwebserverport"></a>

### getWebServerPort()
```php
getWebServerPort(): int
```

---

<a name="method-getwebserverhost"></a>

### getWebServerHost()
```php
getWebServerHost(): string
```

---

<a name="method-iswebserverportavailable"></a>

### isWebServerPortAvailable()
```php
isWebServerPortAvailable(): bool
```

---

<a name="method-launch"></a>

### launch()
```php
launch(): void
```

---

<a name="method-addcontroller"></a>

### addController()
```php
addController(string $controllerClass): void
```