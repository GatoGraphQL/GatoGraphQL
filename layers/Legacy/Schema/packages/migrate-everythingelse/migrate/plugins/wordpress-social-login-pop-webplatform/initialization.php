<?php
class WSL_PoPWebPlatform_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('wsl-pop-webplatform', false, dirname(plugin_basename(__FILE__)).'/languages');

        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        if (!$cmsapplicationapi->isAdminPanel()) {
            \PoP\Root\App::getHookManager()->addAction("popcms:enqueueScripts", array($this, 'registerScripts'));
        }

        /**
         * Library
         */
        include_once 'library/load.php';

        /**
         * Plugins
         */
        include_once 'plugins/load.php';
    }

    public function registerScripts()
    {

        // Only if not doing code splitting then load the resources. Otherwise, the resources will be loaded by the ResourceLoader
        if (PoP_WebPlatform_ConfigurationUtils::registerScriptsAndStylesDuringInit()) {
            $cmswebplatformapi = \PoP\EngineWebPlatform\FunctionAPIFactory::getInstance();
            $js_folder = WSL_POPWEBPLATFORM_URL.'/js';
            $dist_js_folder = $js_folder.'/dist';
            $libraries_js_folder = (PoP_WebPlatform_ServerUtils::useMinifiedResources() ? $dist_js_folder : $js_folder).'/libraries';
            $suffix = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? '.min' : '';
            $bundles_js_folder = $dist_js_folder.'/bundles';

            if (PoP_WebPlatform_ServerUtils::useBundledResources()) {
                $cmswebplatformapi->registerScript('wsl-pop-webplatform', $bundles_js_folder . '/wordpress-social-login-pop.bundle.min.js', array('pop', 'jquery'), WSL_POPWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('wsl-pop-webplatform');
            } else {
                $cmswebplatformapi->registerScript('wsl-pop-webplatform-functions', $libraries_js_folder.'/wsl-functions'.$suffix.'.js', array('jquery', 'pop'), WSL_POPWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('wsl-pop-webplatform-functions');
            }
        }
    }
}
