<?php

class_alias(\PSX\Http\Client\Client::class, 'PSX\Http\Client');
class_alias(\PSX\Http\Client\ClientInterface::class, 'PSX\Http\ClientInterface');
class_alias(\PSX\Http\Client\Cookie::class, 'PSX\Http\Cookie');
class_alias(\PSX\Http\Client\CookieStoreInterface::class, 'PSX\Http\CookieStoreInterface');
class_alias(\PSX\Http\Client\DeleteRequest::class, 'PSX\Http\DeleteRequest');
class_alias(\PSX\Http\Client\GetRequest::class, 'PSX\Http\GetRequest');
class_alias(\PSX\Http\Client\HandlerException::class, 'PSX\Http\HandlerException');
class_alias(\PSX\Http\Client\HandlerInterface::class, 'PSX\Http\HandlerInterface');
class_alias(\PSX\Http\Client\NotSupportedException::class, 'PSX\Http\NotSupportedException');
class_alias(\PSX\Http\Client\Options::class, 'PSX\Http\Options');
class_alias(\PSX\Http\Client\PatchRequest::class, 'PSX\Http\PatchRequest');
class_alias(\PSX\Http\Client\PostRequest::class, 'PSX\Http\PostRequest');
class_alias(\PSX\Http\Client\PutRequest::class, 'PSX\Http\PutRequest');
class_alias(\PSX\Http\Client\RedirectException::class, 'PSX\Http\RedirectException');

class_alias(\PSX\Http\Client\CookieStore\Memory::class, 'PSX\Http\CookieStore\Memory');

class_alias(\PSX\Http\Client\Handler\Callback::class, 'PSX\Http\Handler\Callback');
class_alias(\PSX\Http\Client\Handler\Curl::class, 'PSX\Http\Handler\Curl');
class_alias(\PSX\Http\Client\Handler\Mock::class, 'PSX\Http\Handler\Mock');
class_alias(\PSX\Http\Client\Handler\MockCapture::class, 'PSX\Http\Handler\MockCapture');
class_alias(\PSX\Http\Client\Handler\Socks::class, 'PSX\Http\Handler\Socks');
class_alias(\PSX\Http\Client\Handler\Stream::class, 'PSX\Http\Handler\Stream');

class_alias(\PSX\Http\Parser\ParseException::class, 'PSX\Http\ParseException');
class_alias(\PSX\Http\Parser\ParserAbstract::class, 'PSX\Http\ParserAbstract');
class_alias(\PSX\Http\Parser\RequestParser::class, 'PSX\Http\RequestParser');
class_alias(\PSX\Http\Parser\ResponseParser::class, 'PSX\Http\ResponseParser');
