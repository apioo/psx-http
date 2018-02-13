PSX Http
===

## About

This library contains well designed and tested HTTP interfaces and 
implementations. It has interfaces to abstract HTTP requests from the PHP 
globals (`$_GET`, `$_POST`, etc.) and to build middleware applications. They are 
currently used by the [PSX](http://phpsx.org/) framework and 
[Fusio](https://www.fusio-project.org/) but the implementations can also be used
independently.

We are aware that this overlaps with PSR-7 and PSR-15 but we think that those 
specs have made some bad design decisions and this project is here to provide an 
alternative. It is always good to have diversity and evolution will show which 
is the better design. Also we should note the fitting [XKDC](https://xkcd.com/927/).

### HTTP

#### `RequestInterface`

```
+ getRequestTarget(): string
+ setRequestTarget(string $requestTarget)
+ getMethod(): string
+ setMethod(string $method)
+ getUri(): Uri
+ setUri(Uri $uri)
+ getAttributes(): string|null
+ getAttribute(string $name)
+ setAttribute(string $name, mixed $value)
+ removeAttribute(string $name)
```

#### `ResponseInterface`

```
+ getStatusCode(): integer
+ getReasonPhrase(): string
+ setStatus(integer $code, string $reasonPhrase = null)
```

#### `MessageInterface`

```
+ getProtocolVersion(): string
+ setProtocolVersion(string $protocol)
+ getHeaders(): array
+ setHeaders(array $headers)
+ hasHeader(string $name): bool
+ getHeader(string $name): string|null
+ getHeaderLines(string $name): array
+ setHeader(string $name, string $value)
+ addHeader(string $name, string $value)
+ removeHeader(string $name)
+ getBody(): StreamInterface
+ setBody(StreamInterface $body)
```

### HTTP Body

#### `StreamInterface`

```
+ close()
+ detach(): resource
+ getSize(): integer
+ tell(): integer
+ eof(): boolean
+ isSeekable(): boolean
+ seek(integer $offset, $whence = SEEK_SET)
+ rewind()
+ isWritable(): boolean
+ write(string $string): integer
+ isReadable(): boolean
+ read(integer $length): string
+ getContents(): string
+ getMetadata(string $key = null): string
```

### HTTP Middleware

#### `FilterInterface`

```
+ handle(RequestInterface $request, ResponseInterface $response, FilterChainInterface $filterChain)
```

#### `FilterChainInterface`

```
+ on(FilterInterface|\Closure $filter)
+ handle(RequestInterface $request, ResponseInterface $response)
```

### HTTP Client

#### `ClientInterface`

```
+ request(RequestInterface $request, OptionsInterface $options = null)
```

#### `OptionsInterface`

```
+ getAllowRedirects(): boolean
+ getCert(): string
+ getProxy(): string
+ getSslKey(): string
+ getVerify(): boolean
+ getTimeout(): float
+ getVersion(): float
```

## Middleware

The following shows a simple middleware which always returns the response body
`Hello World!`:

```php
<?php

use PSX\Http;
use PSX\Http\Server;

$chain = new Http\Filter\FilterChain();
$chain->on(function(Http\RequestInterface $request, Http\ResponseInterface $response, Http\FilterChainInterface $filterChain){
    // get query parameter
    $request->getUri()->getParameter('foo');
    
    // set header
    $response->setHeader('X-Foo', 'bar');
    
    // write data to the body
    $response->getBody()->write('Hello World!');
    
    $filterChain->handle($request, $response);
});

$request  = (new Server\RequestFactory())->createRequest();
$response = (new Server\ResponseFactory())->createResponse();

$chain->handle($request, $response);

(new Server\Sender())->send($response);
```

## Client

The following sends a HTTP GET request to google:

```php
<?php

use PSX\Http\Client;

$client   = new Client\Client();
$request  = new Client\GetRequest('http://google.com', ['Accept' => 'text/html']);
$response = $client->request($request);

if ($response->getStatusCode() == 200) {
    echo (string) $response->getBody();
} else {
    // something goes wrong
}
```

## Distinction

### PSR-7

* The classes are mutable (`set*` instead of `with*`), you can change the state
  of the object.
* There is no `ServerRequestInterface` and `UploadedFileInterface`
* There is only a single way to access query parameters
* `getHeader` returns a string instead of an array which is the 80% case

### Thoughts

* Because PSR-7 is immutable PSR-15 must have the `fn(req): res` signature since
  it is not possible to change the response object.
* The middleware needs to know how to create an HTTP response instance. Because
  of this you can't inject a different response implementation into your 
  middleware stack. As workaround we see a HTTP factory PSR, but we think this 
  is a code-smell.
* If your app uses a PHP server like Swoole you want to wrap the Swoole response 
  object and pass it to the middleware to handle also streaming uses cases.
* Immutability forces a design on your application you have i.e. not the
  option to use the double-pass middleware signature.
* Since PHP has no immutability on the language level we must 
  always copy the object and change a specific value which is bad for memory /
  performance.
* It is really difficult to migrate legacy applications to the
  `fn(req): res` middleware style since most applications today work with an
  mutable HTTP object.
* PSR-7 is actually not fully immutable since the body is always mutable
