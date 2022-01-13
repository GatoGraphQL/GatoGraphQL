<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
class PoP_UserPlatformWebPlatform_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-userplatform-webplatform', false, dirname(plugin_basename(__FILE__)).'/languages');

        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        if (!$cmsapplicationapi->isAdminPanel()) {
            HooksAPIFacade::getInstance()->addAction('popcms:enqueueScripts', array($this, 'registerScripts'));
        }

        /**
         * Load the PoP Library
         */
        include_once 'library/load.php';

        /**
         * Load the Plugins Library
         */
        include_once 'plugins/load.php';
    }

    public function registerScripts()
    {

        // Only if not doing code splitting then load the resources. Otherwise, the resources will be loaded by the ResourceLoader
        if (PoP_WebPlatform_ConfigurationUtils::registerScriptsAndStylesDuringInit()) {
            $cmswebplatformapi = \PoP\EngineWebPlatform\FunctionAPIFactory::getInstance();
            $js_folder = POP_USERPLATFORMWEBPLATFORM_URL.'/js';
            $dist_js_folder = $js_folder.'/dist';
            $libraries_js_folder = (PoP_WebPlatform_ServerUtils::useMinifiedResources() ? $dist_js_folder : $js_folder).'/libraries';
            $suffix = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? '.min' : '';
            $bundles_js_folder = $dist_js_folder.'/bundles';

            if (PoP_WebPlatform_ServerUtils::useBundledResources()) {
                $cmswebplatformapi->registerScript('pop-userplatform-webplatform', $bundles_js_folder . '/pop-userplatform.bundle.min.js', array(), POP_USERPLATFORMWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-userplatform-webplatform');
            } else {
                $cmswebplatformapi->registerScript('pop-userplatformwebplatform-user-platform-account', $libraries_js_folder.'/user-platform-account'.$suffix.'.js', array('jquery', 'pop'), POP_USERPLATFORMWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-userplatformwebplatform-user-platform-account');
            }
        }
    }
}
