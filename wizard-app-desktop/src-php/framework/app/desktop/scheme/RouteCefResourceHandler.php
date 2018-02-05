<?php
namespace framework\app\desktop\scheme;

use cef\CefCallback;
use cef\CefResourceHandler;
use cef\CefResponse;
use php\io\Stream;
use php\lib\str;

/**
 * Class RouteCefResourceHandler
 * @package framework\app\desktop\scheme
 */
class RouteCefResourceHandler extends CefResourceHandler
{
    /**
     * @var callable
     */
    private $handler;

    /**
     * @var RouteRequest
     */
    private $request;

    /**
     * @var RouteResponse
     */
    private $response;

    /**
     * @var int
     */
    private $offset = 0;

    /**
     * RouteCefResourceHandler constructor.
     * @param callable $handler
     */
    public function __construct(callable $handler)
    {
        $this->handler = $handler;
    }


    /**
     * @param array $request
     * @param CefCallback $callback
     * @return bool
     */
    public function processRequest(array $request, CefCallback $callback)
    {
        $this->request = new RouteRequest();
        $this->request->url = $request['uRL'];
        $this->request->method = $request['method'];

        $this->response = new RouteResponse();

        call_user_func($this->handler, $this->request, $this->response);

        $callback->continue();
        return true;
    }

    /**
     * @param CefResponse $response
     * @param array $args [length, redirectUrl]
     * @return array|null [length, redirectUrl]
     */
    public function getResponseHeaders(CefResponse $response, array $args)
    {
        if ($this->response->contentType) {
            $response->mimeType = $this->response->contentType;
        }

        if ($this->response->status) {
            $response->status = $this->response->status;
        }

        if ($this->response->headers) {
            $response->headerMap = $this->response->headers;
        }

        if ($this->response->contentLength) {
            return ['length' => $this->response->contentLength];
        } else {
            return ['length' => str::length($this->response->body)];
        }
    }

    /**
     * @param Stream $out
     * @param int $bytesToRead
     * @param CefCallback $callback
     */
    public function readResponse(Stream $out, int $bytesToRead, CefCallback $callback)
    {
        $len = min($bytesToRead, str::length($this->response->body) - $this->offset);

        if ($len <= str::length($this->response->body)) {
            $str = str::sub($this->response->body, $this->offset, $this->offset + $len);

            if ($str !== '') {
                $out->write($str);
                $this->offset += $len;
            }
        }
    }

    /**
     * @param array $cookie
     * @return bool
     */
    public function canGetCookie(array $cookie)
    {
        return false;
    }

    /**
     * @param array $cookie
     * @return bool
     */
    public function canSetCookie(array $cookie)
    {
        return false;
    }

    /**
     */
    public function cancel()
    {
        $this->response = null;
        $this->request = null;
    }
}