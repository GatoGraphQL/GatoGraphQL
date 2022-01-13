<?php
class PoP_GoogleAnalytics_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-googleanalytics', false, dirname(plugin_basename(__FILE__)).'/languages');

        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        if (!$cmsapplicationapi->isAdminPanel()) {
            \PoP\Root\App::addAction("popcms:enqueueScripts", array($this, 'registerScripts'));
        }

        /**
         * Load the Interfaces
         */
        include_once 'interfaces/load.php';

        /**
         * Plugins Library
         */
        include_once 'plugins/load.php';
    }

    public function registerScripts()
    {

        // Only if not doing code splitting then load the resources. Otherwise, the resources will be loaded by the ResourceLoader
        if (PoP_WebPlatform_ConfigurationUtils::registerScriptsAndStylesDuringInit()) {
            $cmswebplatformapi = \PoP\EngineWebPlatform\FunctionAPIFactory::getInstance();
            $js_folder = POP_GOOGLEANALYTICS_URL.'/js';
            $dist_js_folder = $js_folder.'/dist';
            $libraries_js_folder = (PoP_WebPlatform_ServerUtils::useMinifiedResources() ? $dist_js_folder : $js_folder).'/libraries';
            $suffix = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? '.min' : '';
            $bundles_js_folder = $dist_js_folder.'/bundles';

            if (PoP_WebPlatform_ServerUtils::useBundledResources()) {
                $cmswebplatformapi->registerScript('pop-googleanalytics', $bundles_js_folder . '/pop-googleanalytics.bundle.min.js', array('pop', 'jquery'), POP_GOOGLEANALYTICS_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-googleanalytics');
            } else {
                $cmswebplatformapi->registerScript('pop-googleanalytics-functions', $libraries_js_folder.'/ga-functions'.$suffix.'.js', array('jquery', 'pop'), POP_GOOGLEANALYTICS_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-googleanalytics-functions');
            }
        }
    }
}
