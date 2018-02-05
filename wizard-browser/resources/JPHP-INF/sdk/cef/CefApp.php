<?php

namespace cef;

/**
 * Class CefApp
 * @package cef
 */
class CefApp
{
    /**
     * CefApp constructor.
     */
    private function __construct()
    {
    }

    /**
     * [
     *  CEF_VERSION_MAJOR => ...
     *  CHROME_VERSION_MAJOR => ...
     *  CHROME_VERSION_MINOR => ...
     * ]
     * @return array|null
     */
    public function getVersion(): ?array
    {
    }

    /**
     * [
     *  browser_subprocess_path => null (string), windowless_rendering_enabled => true, command_line_args_disabled => false,
     *  cache_path => null, persist_session_cookies => false, user_agent => null (string), product_version => null (string),
     *  locale => null (string), log_file => null, log_severity => LOGSEVERITY_DEFAULT,
     *  javascript_flags => null (string), resources_dir_path => null (string), locales_dir_path => null (string),
     *  pack_loading_disabled => false, remote_debugging_port => 0, uncaught_exception_stack_size => 0,
     *  ignore_certificate_errors => false, background_color => null
     * ]
     * @param array $settings
     */
    public function setSettings(array $settings)
    {
    }

    /**
     * To shutdown the system, it's important to call the dispose
     * method. Calling this method closes all client instances with
     * and all browser instances each client owns. After that the
     * message loop is terminated and CEF is shutdown.
     */
    public function dispose()
    {
    }

    /**
     * @return CefClient
     */
    public function createClient(): CefClient
    {
    }

    /**
     * NONE, NEW, INITIALIZING, INITIALIZED, SHUTTING_DOWN, TERMINATED.
     *
     * @return string
     */
    public static function getState(): string
    {
    }

    /**
     * @param array $args
     * @param array $settings
     * @return CefApp
     */
    public static function getInstance(array $args = [], array $settings = []): CefApp
    {
    }

    /**
     * @param callable $invoker (): bool
     */
    public static function onBeforeTerminate(callable $invoker)
    {
    }

    /**
     * @param callable $invoker (): void
     */
    public static function onContextInitialized(callable $invoker)
    {
    }

    /**
     * State is - NONE, NEW, INITIALIZING, INITIALIZED, SHUTTING_DOWN, TERMINATED
     * @param callable $invoker (string $state): void
     */
    public static function onStateChanged(callable $invoker)
    {
    }

    /**
     * @param string $schemeName
     * @param callable $factory (CefBrowser $browser, string $schemeName, array $request): CefResourceHandler
     * @param bool $isStandard
     */
    public static function registerCustomScheme(string $schemeName, callable $factory, bool $isStandard = true)
    {
    }
}