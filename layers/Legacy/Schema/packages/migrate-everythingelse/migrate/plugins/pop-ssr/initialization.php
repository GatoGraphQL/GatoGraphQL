<?php
class PoP_SSR_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-ssr', false, dirname(plugin_basename(__FILE__)).'/languages');

        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        if (!$cmsapplicationapi->isAdminPanel()) {
            \PoP\Root\App::getHookManager()->addAction('popcms:enqueueScripts', array($this, 'registerScripts'));
        }

        /**
         * Load the Kernel
         */
        include_once 'kernel/load.php';

        /**
         * Load the PoP Library
         */
        include_once 'library/load.php';

        /**
         * Load the Plugins
         */
        include_once 'plugins/load.php';
    }

    public function registerScripts()
    {

        // Only if not doing code splitting then load the resources. Otherwise, the resources will be loaded by the ResourceLoader
        if (PoP_WebPlatform_ConfigurationUtils::registerScriptsAndStylesDuringInit()) {
            $cmswebplatformapi = \PoP\EngineWebPlatform\FunctionAPIFactory::getInstance();
            $js_folder = POP_SSR_URL.'/js';
            $dist_js_folder = $js_folder.'/dist';
            $libraries_js_folder = (PoP_WebPlatform_ServerUtils::useMinifiedResources() ? $dist_js_folder : $js_folder).'/libraries';
            $suffix = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? '.min' : '';
            $bundles_js_folder = $dist_js_folder.'/bundles';

            if (PoP_WebPlatform_ServerUtils::useBundledResources()) {
                $cmswebplatformapi->registerScript('pop-ssr', $bundles_js_folder . '/pop-ssr.bundle.min.js', array(), POP_SSR_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-ssr');
            } else {
                $cmswebplatformapi->registerScript('pop-ssr-functions', $libraries_js_folder.'/ssr'.$suffix.'.js', array('pop'), POP_SSR_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-ssr-functions');
            }
        }
    }
}
