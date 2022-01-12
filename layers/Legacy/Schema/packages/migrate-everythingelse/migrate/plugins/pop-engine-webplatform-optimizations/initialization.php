<?php

use PoP\ComponentModel\ComponentConfiguration as ComponentModelComponentConfiguration;
use PoP\Hooks\Facades\HooksAPIFacade;

class PoP_WebPlatformEngineOptimizations_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-engine-webplatform-optimizations', false, dirname(plugin_basename(__FILE__)).'/languages');

        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        if (!$cmsapplicationapi->isAdminPanel()) {
            HooksAPIFacade::getInstance()->addAction('popcms:enqueueScripts', array($this, 'registerScripts'));
        }

        /**
         * Load the Kernel
         */
        include_once 'kernel/load.php';

        /**
         * Load the Library
         */
        include_once 'library/load.php';

        /**
         * Load the Plug-ins Library
         */
        include_once 'plugins/load.php';
    }

    public function registerScripts()
    {

        // Only if not doing code splitting then load the resources. Otherwise, the resources will be loaded by the Engine Web PlatformOptimizations
        if (PoP_WebPlatform_ConfigurationUtils::registerScriptsAndStylesDuringInit()) {
            $cmswebplatformapi = \PoP\EngineWebPlatform\FunctionAPIFactory::getInstance();
            $js_folder = POP_ENGINEWEBPLATFORMOPTIMIZATIONS_URL.'/js';
            $dist_js_folder = $js_folder.'/dist';
            $libraries_js_folder = (PoP_WebPlatform_ServerUtils::useMinifiedResources() ? $dist_js_folder : $js_folder).'/libraries';
            $suffix = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? '.min' : '';
            $bundles_js_folder = $dist_js_folder.'/bundles';

            if (PoP_WebPlatform_ServerUtils::useBundledResources()) {
                $cmswebplatformapi->registerScript('pop-engine-webplatform-optimizations', $bundles_js_folder . '/pop-engine-webplatform-optimizations.bundle.min.js', array(), POP_ENGINEWEBPLATFORMOPTIMIZATIONS_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-engine-webplatform-optimizations');
            } else {
                // Comment Leo 19/11/2017: if we enable the "config" param, then add this resource always
                // (Otherwise it fails because the configuration is cached but the list of modules to load is different)
                // If not, then add it if we are generating the resources on runtime
                if (PoP_WebPlatformEngineOptimizations_ServerUtils::extractResponseIntoJsfilesOnRuntime()) {
                    $cmswebplatformapi->registerScript('pop-initializedata', $libraries_js_folder.'/initializedata'.$suffix.'.js', array(), POP_ENGINEWEBPLATFORMOPTIMIZATIONS_VERSION, true);
                    $cmswebplatformapi->enqueueScript('pop-initializedata');
                }
            }
        }
    }
}
