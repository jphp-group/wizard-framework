<?php
namespace framework\app\desktop\scheme;

use cef\CefCallback;
use cef\CefResourceHandler;
use cef\CefResponse;
use framework\core\Logger;
use php\io\IOException;
use php\io\Stream;
use php\lib\fs;
use php\lib\str;

class StreamCefResourceHandler extends CefResourceHandler
{
    /**
     * @var string
     */
    private $source;

    /**
     * @var Stream
     */
    private $stream;

    public function __construct(string $source)
    {
        $this->source = $source;
    }


    /**
     * @param array $request
     * @param CefCallback $callback
     * @return bool
     */
    public function processRequest(array $request, CefCallback $callback)
    {
        try {
            $this->stream = Stream::of($this->source);
            $callback->continue();
            return true;
        } catch (IOException $e) {
            Logger::warn("Resource {0} not found, {1}", $this->source, $e->getMessage());
            $callback->cancel();
            return false;
        }
    }

    /**
     * @param CefResponse $response
     * @param array $args [length, redirectUrl]
     * @return array|null [length, redirectUrl]
     */
    public function getResponseHeaders(CefResponse $response, array $args)
    {
        $response->status = 200;

        $ext = str::lower(fs::ext($this->source));

        switch ($ext) {
            case "css": $mimeType = "text/css"; break;
            case "js": $mimeType = "text/javascript"; break;
            case "svg": $mimeType = "image/svg+xml"; break;
            case "png": $mimeType = "image/png"; break;
            case "jpg": $mimeType = "image/jpeg"; break;
            case "jpeg": $mimeType = "image/jpeg"; break;
            case "gif": $mimeType = "image/gif"; break;
            case "txt": $mimeType = "text/plain"; break;
            case "html": $mimeType = "text/html"; break;
            case "json": $mimeType = "application/json"; break;
            default:
                $mimeType = "application/octet-stream";
                break;
        }

        $response->mimeType = $mimeType;

        return ['length' => PHP_INT_MAX];
    }

    /**
     * @param Stream $out
     * @param int $bytesToRead
     * @param CefCallback $callback
     */
    public function readResponse(Stream $out, int $bytesToRead, CefCallback $callback)
    {
        $part = $this->stream->read($bytesToRead);

        if ($part) {
            $out->write($part);
        } else {
            $this->stream->close();
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
        if ($this->stream) {
            $this->stream->close();
        }
    }
}