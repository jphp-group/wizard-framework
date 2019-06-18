<?php
namespace framework\app;

use framework\core\Component;

abstract class AbstractWebAppModule extends Component
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
     * AbstractWebAppModule constructor.
     */
    public function __construct()
    {
        parent::__construct();

        include_once "res://.inc/ui-functions.php";
    }

    /**
     * Enable rich user interface.
     * @param string $jsFile
     * @param string $cssFile
     * @return $this
     */
    public function setupResources(string $jsFile = "", string $cssFile = "")
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