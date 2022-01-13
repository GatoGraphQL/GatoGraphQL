<?php
class PoP_TinyMCEWebPlatform_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-tinymce-webplatform', false, dirname(plugin_basename(__FILE__)).'/languages');

        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        if (!$cmsapplicationapi->isAdminPanel()) {
            \PoP\Root\App::getHookManager()->addAction('popcms:enqueueScripts', array($this, 'registerScripts'));
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
        $cmswebplatformapi = \PoP\EngineWebPlatform\FunctionAPIFactory::getInstance();

        // Media Player for the Resources section
        $cmswebplatformapi->enqueueScript('wp-mediaelement');

        // Only if not doing code splitting then load the resources. Otherwise, the resources will be loaded by the ResourceLoader
        if (PoP_WebPlatform_ConfigurationUtils::registerScriptsAndStylesDuringInit()) {
            $js_folder = POP_TINYMCEWEBPLATFORM_URL.'/js';
            $dist_js_folder = $js_folder.'/dist';
            $libraries_js_folder = (PoP_WebPlatform_ServerUtils::useMinifiedResources() ? $dist_js_folder : $js_folder).'/libraries';
            $suffix = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? '.min' : '';
            $bundles_js_folder = $dist_js_folder.'/bundles';

            if (PoP_WebPlatform_ServerUtils::useBundledResources()) {
                $cmswebplatformapi->registerScript('pop-tinymce-webplatform', $bundles_js_folder . '/pop-tinymce.bundle.min.js', array(), POP_TINYMCEWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-tinymce-webplatform');
            } else {
                $cmswebplatformapi->registerScript('pop-tinymce-webplatform-editor', $libraries_js_folder.'/editor'.$suffix.'.js', array('jquery', 'pop'), POP_TINYMCEWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-tinymce-webplatform-editor');
            }
        }
    }
}
