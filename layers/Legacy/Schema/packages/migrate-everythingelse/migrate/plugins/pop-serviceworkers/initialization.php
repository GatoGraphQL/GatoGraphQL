<?php
class PoP_ServiceWorkers_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-serviceworkers', false, dirname(plugin_basename(__FILE__)).'/languages');

        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        if (!$cmsapplicationapi->isAdminPanel()) {
            \PoP\Root\App::addAction('popcms:enqueueScripts', $this->registerScripts(...));
            \PoP\Root\App::addAction('popcms:head', $this->header(...));
        }

        /**
         * Constants/Configuration for functionalities needed by the plug-in
         */
        // require_once 'config/load.php';

        /**
         * Load the Vendor library
         */
        // require_once 'vendor/autoload.php';
        // require_once 'vendor/load.php';

        /**
         * Kernel
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

    public function header()
    {

        // Print the reference to the manifest file
        global $pop_serviceworkers_manager;
        printf(
            '<link rel="manifest" href="%s">',
            $pop_serviceworkers_manager->getFileurl('manifest.json')
        );
    }

    public function registerScripts()
    {

        // Only if not doing code splitting then load the resources. Otherwise, the resources will be loaded by the ResourceLoader
        if (PoP_WebPlatform_ConfigurationUtils::registerScriptsAndStylesDuringInit()) {
            $cmswebplatformapi = \PoP\EngineWebPlatform\FunctionAPIFactory::getInstance();
            $js_folder = POP_SERVICEWORKERS_URL.'/js';
            $dist_js_folder = $js_folder.'/dist';
            $libraries_js_folder = (PoP_WebPlatform_ServerUtils::useMinifiedResources() ? $dist_js_folder : $js_folder).'/libraries';
            $suffix = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? '.min' : '';
            $bundles_js_folder = $dist_js_folder.'/bundles';

            if (PoP_WebPlatform_ServerUtils::useBundledResources()) {
                $cmswebplatformapi->registerScript('pop-serviceworkers', $bundles_js_folder . '/pop-serviceworkers.bundle.min.js', array(), POP_SERVICEWORKERS_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-serviceworkers');
            } else {
                $cmswebplatformapi->registerScript('pop-serviceworkers-functions', $libraries_js_folder.'/sw'.$suffix.'.js', array('pop'), POP_SERVICEWORKERS_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-serviceworkers-functions');

                $cmswebplatformapi->registerScript('pop-serviceworkers-functions-initial', $libraries_js_folder.'/sw-initial'.$suffix.'.js', array('pop'), POP_SERVICEWORKERS_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-serviceworkers-functions-initial');
            }

            if (!PoP_ServiceWorkers_ServerUtils::disableServiceworkers()) {
                // This file is generated dynamically, so it can't be added to any bundle or minified
                global $pop_serviceworkers_manager;
                $cmswebplatformapi->registerScript('pop-serviceworkers-registrar', $pop_serviceworkers_manager->getFileurl('sw-registrar.js'), array(), POP_SERVICEWORKERS_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-serviceworkers-registrar');
            }
        }
    }
}
