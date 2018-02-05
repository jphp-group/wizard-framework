<?php
namespace cef;
use php\io\Stream;

/**
 * Class CefResourceHandler
 * @package cef
 */
abstract class CefResourceHandler
{
    /**
     * @param array $request
     * @param CefCallback $callback
     * @return bool
     */
    abstract public function processRequest(array $request, CefCallback $callback);

    /**
     * @param CefResponse $response
     * @param array $args [length, redirectUrl]
     * @return array|null [length, redirectUrl]
     */
    abstract public function getResponseHeaders(CefResponse $response, array $args);

    /**
     * @param Stream $out
     * @param int $bytesToRead
     * @param CefCallback $callback
     */
    abstract public function readResponse(Stream $out, int $bytesToRead, CefCallback $callback);

    /**
     * @param array $cookie
     * @return bool
     */
    abstract public function canGetCookie(array $cookie);

    /**
     * @param array $cookie
     * @return bool
     */
    abstract public function canSetCookie(array $cookie);

    /**
     */
    abstract public function cancel();
}