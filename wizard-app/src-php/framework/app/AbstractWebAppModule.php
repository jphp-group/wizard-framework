<?php
namespace framework\app;

use framework\core\Module;

include_once "res://.inc/ui-functions.php";

abstract class AbstractWebAppModule extends Module
{
    /**
     * @var array
     */
    protected $uiClasses = [];

    /**
     * @var string
     */
    protected $dnextJsFile = null;

    /**
     * @var string
     */
    protected $dnextCssFile = null;

    /**
     * @var array
     */
    protected $dnextResources = [];

    /**
     * Enable rich user interface.
     * @param string $jsFile
     * @param string $cssFile
     * @return $this
     */
    public function setupResources(string $jsFile = '', string $cssFile = '')
    {
        $this->dnextCssFile = $cssFile;
        $this->dnextJsFile = $jsFile;

        return $this;
    }

    /**
     * @param string $uiClass
     * @return $this
     */
    abstract public function addUI(string $uiClass);
}