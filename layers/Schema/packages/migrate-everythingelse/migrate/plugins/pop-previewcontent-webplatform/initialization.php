<?php
use PoP\Hooks\Facades\HooksAPIFacade;
class PoP_PreviewContentWebPlatform_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('ppp-pop-webplatform', false, dirname(plugin_basename(__FILE__)).'/languages');

        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        if (!$cmsapplicationapi->isAdminPanel()) {
            HooksAPIFacade::getInstance()->addAction("popcms:enqueueScripts", array($this, 'registerScripts'));
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
            $js_folder = POP_PREVIEWCONTENTWEBPLATFORM_URL.'/js';
            $dist_js_folder = $js_folder.'/dist';
            $libraries_js_folder = (PoP_WebPlatform_ServerUtils::useMinifiedResources() ? $dist_js_folder : $js_folder).'/libraries';
            $suffix = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? '.min' : '';
            $bundles_js_folder = $dist_js_folder.'/bundles';

            if (PoP_WebPlatform_ServerUtils::useBundledResources()) {
                $cmswebplatformapi->registerScript('pop-previewcontent-webplatform', $bundles_js_folder . '/pop-previewcontent.bundle.min.js', array('pop', 'jquery'), POP_PREVIEWCONTENTWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-previewcontent-webplatform');
            } else {
                $cmswebplatformapi->registerScript('ppp-pop-webplatform-functions', $libraries_js_folder.'/previewcontent'.$suffix.'.js', array('jquery', 'pop'), POP_PREVIEWCONTENTWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('ppp-pop-webplatform-functions');
            }
        }
    }
}
