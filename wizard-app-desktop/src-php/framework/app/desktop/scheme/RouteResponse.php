<?php
namespace framework\app\desktop\scheme;

/**
 * Class RouteResponse
 * @package framework\app\desktop\scheme
 */
class RouteResponse
{
    /**
     * @var int
     */
    public $status = 200;

    /**
     * @var int
     */
    public $contentLength = -1;

    /**
     * @var string
     */
    public $contentType = 'text/plain';

    /**
     * @var array
     */
    public $headers = [];

    /**
     * @var string
     */
    public $body = '';
}