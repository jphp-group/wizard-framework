<?php
namespace cef;

/**
 * Class CefResponse
 * @package cef
 */
class CefResponse
{
    /**
     * @var bool
     */
    public $readOnly = false;

    /**
     * @var int
     */
    public $status = 0;

    /**
     * @var string
     */
    public $statusText = '';

    /**
     * @var string
     */
    public $mimeType = '';

    /**
     * ERROR code.
     * @var string
     */
    public $error = 'ERR_NONE';

    /**
     * @var array
     */
    public $headerMap = [];

    /**
     * CefResponse constructor.
     */
    public function __construct()
    {
    }
}