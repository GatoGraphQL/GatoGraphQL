<?php
use PoP\ComponentModel\Misc\RequestUtils;

class PoP_ServiceWorkers_WebPlatformEngineOptimization_ResourceLoader_Initialization
{
    public function __construct()
    {

        // Priority 70: after the `enqueueScripts` function in wp-content/plugins/pop-engine/kernel/pop-engine.php
        \PoP\Root\App::addAction('popcms:enqueueScripts', $this->deregisterScripts(...), 70);
    }

    public function deregisterScripts()
    {

        // Register the scripts from the Resource Loader on the current request
        // Only if loading the frame, and it is configured to use code splitting
        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        $cmswebplatformapi = \PoP\EngineWebPlatform\FunctionAPIFactory::getInstance();
        if (!$cmsapplicationapi->isAdminPanel() && RequestUtils::loadingSite() && PoP_ResourceLoader_ServerUtils::useCodeSplitting()) {
            // Dequeue unwanted scripts. Eg: the /settings/ runtime generated files, which correspond to the /generate/ page and will not be needed by anyone else
            if (PoP_WebPlatformEngineOptimizations_ServerUtils::extractResponseIntoJsfilesOnRuntime()) {
                $properties = array(
                    array('statelessdata', 'settings'),
                );
                foreach ($properties as $property_path) {
                    $cmswebplatformapi->dequeueScript('pop-'.implode('-', $property_path));
                }
            }
        }
    }
}

/**
 * Initialization
 */
// It is initialized inside function systemGenerate()
// new PoP_ServiceWorkers_WebPlatformEngineOptimization_ResourceLoader_Initialization();
