
# HTTP

This library contains elegant interfaces to describe HTTP message, middleware and client classes. It contains also
corresponding reference implementations which can be used by every app which needs a solid HTTP stack.

## Interfaces

### HTTP

* [RequestInterface](./src/RequestInterface.php)
* [ResponseInterface](./src/ResponseInterface.php)
* [MessageInterface](./src/MessageInterface.php)

### HTTP Body

* [StreamInterface](./src/StreamInterface.php)

### HTTP Middleware

* [FilterInterface](./src/FilterInterface.php)
* [FilterChainInterface](./src/FilterChainInterface.php)

### HTTP Client

* [ClientInterface](./src/Client/ClientInterface.php)
* [OptionsInterface](./src/Client/OptionsInterface.php)

## Examples

### Middleware

The following shows a simple middleware which always returns the response body `Hello World!`:

```php
<?php

use PSX\Http;
use PSX\Http\Server;

$chain = new Http\Filter\FilterChain();

// enforce user agent in HTTP request
$chain->on(new Http\Filter\UserAgentEnforcer());

// display maintenance file if available
$chain->on(new Http\Filter\Backstage(__DIR__ . '/.maintenance.html'));

// closure middleware
$chain->on(function(Http\RequestInterface $request, Http\ResponseInterface $response, Http\FilterChainInterface $filterChain){
    // get query parameter
    $request->getUri()->getParameter('foo');
    
    // set header
    $response->setHeader('X-Foo', 'bar');
    
    // write data to the body
    $response->getBody()->write('Hello World!');
    
    $filterChain->handle($request, $response);
});

// create global HTTP request and response
$request  = (new Server\RequestFactory())->createRequest();
$response = (new Server\ResponseFactory())->createResponse();

// start middleware chain
$chain->handle($request, $response);

// send response
(new Server\Sender())->send($response);
```

### Client

The following sends an HTTP GET request to google:

```php
<?php

use PSX\Http\Client;
use PSX\Http\Exception\StatusCodeException;

// create HTTP client
$client = new Client\Client();

// build request
$request = new Client\GetRequest('https://google.com', ['Accept' => 'text/html']);

// send request
$response = $client->request($request);

// check response
if ($response->getStatusCode() == 200) {
    // get header
    $contentType = $response->getHeader('Content-Type');

    // output response body
    echo (string) $response->getBody();
} else {
    // the client never throws an exception for unsuccessful response codes but
    // you can do this explicit
    StatusCodeException::throwOnError($response);
}
```

### Uploads

Example how to handle file uploads:

```php
<?php

use PSX\Http;
use PSX\Http\Server;

$chain = new Http\Filter\FilterChain();

// closure middleware
$chain->on(function(Http\RequestInterface $request, Http\ResponseInterface $response, Http\FilterChainInterface $filterChain){
    // get body
    $body = $request->getBody();

    if ($body instanceof Http\Stream\MultipartStream) {
        // move uploaded file to a new location
        $body->getPart('userfile')->move('/home/new/file.txt');

        // or access the file directly through the normal stream functions
        //$body->getPart('userfile')->read(32);

        // write data to the body
        $response->getBody()->write('Upload successful!');
    } else {
        // no upload so show form
        $html = <<<'HTML'
<!-- The data encoding type, enctype, MUST be specified as below -->
<form enctype="multipart/form-data" action="" method="POST">
    <!-- MAX_FILE_SIZE must precede the file input field -->
    <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
    <!-- Name of input element determines name in $_FILES array -->
    Send this file: <input name="userfile" type="file" />
    <input type="submit" value="Send File" />
</form>
HTML;

        $response->getBody()->write($html);
    }

    $filterChain->handle($request, $response);
});

// create global HTTP request and response
$request  = (new Server\RequestFactory())->createRequest();
$response = (new Server\ResponseFactory())->createResponse();

// start middleware chain
$chain->handle($request, $response);

// send response
(new Server\Sender())->send($response);
```
