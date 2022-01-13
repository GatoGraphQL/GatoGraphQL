<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
class PoP_ContentCreationWebPlatform_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-contentcreation-webplatform', false, dirname(plugin_basename(__FILE__)).'/languages');

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
            $js_folder = POP_CONTENTCREATIONWEBPLATFORM_URL.'/js';
            $dist_js_folder = $js_folder.'/dist';
            $libraries_js_folder = (PoP_WebPlatform_ServerUtils::useMinifiedResources() ? $dist_js_folder : $js_folder).'/libraries';
            $suffix = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? '.min' : '';
            $bundles_js_folder = $dist_js_folder.'/bundles';

            if (PoP_WebPlatform_ServerUtils::useBundledResources()) {
                $cmswebplatformapi->registerScript('pop-contentcreation-webplatform', $bundles_js_folder . '/pop-contentcreation.bundle.min.js', array(), POP_CONTENTCREATIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-contentcreation-webplatform');
                
                $cmswebplatformapi->registerScript('pop-contentcreation-webplatform-templates', $bundles_js_folder . '/pop-contentcreation.templates.bundle.min.js', array(), POP_CONTENTCREATIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-contentcreation-webplatform-templates');
            } else {
                $cmswebplatformapi->registerScript('pop-contentcreation-featuredimage', $libraries_js_folder.'/featuredimage'.$suffix.'.js', array('jquery', 'pop'), POP_CONTENTCREATIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-contentcreation-featuredimage');

                // Watch out: crossdomain comes after featuredimage! Because it needs to access its internal variables, after these were initialized
                // And it depends on the user-account, to track when the user logs in/out
                $cmswebplatformapi->registerScript('pop-contentcreation-mediamanager-state', $libraries_js_folder.'/mediamanager/mediamanager-state'.$suffix.'.js', array('jquery', 'pop', 'pop-contentcreation-featuredimage'), POP_CONTENTCREATIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-contentcreation-mediamanager-state');

                $cmswebplatformapi->registerScript('pop-contentcreation-mediamanager', $libraries_js_folder.'/mediamanager/mediamanager'.$suffix.'.js', array('jquery', 'pop', 'pop-contentcreation-featuredimage'), POP_CONTENTCREATIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-contentcreation-mediamanager');

                // Watch out: crossdomain comes after mediamanager! Because it depends on it
                $cmswebplatformapi->registerScript('pop-contentcreation-mediamanager-cors', $libraries_js_folder.'/mediamanager/mediamanager-cors'.$suffix.'.js', array('jquery', 'pop', 'pop-contentcreation-mediamanager'), POP_CONTENTCREATIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-contentcreation-mediamanager-cors');

                // Templates
                $this->enqueueTemplatesScripts();
            }
        }
    }

    public function enqueueTemplatesScripts()
    {
        $cmswebplatformapi = \PoP\EngineWebPlatform\FunctionAPIFactory::getInstance();
        $folder = POP_CONTENTCREATIONWEBPLATFORM_URL.'/js/dist/templates/';

        $cmswebplatformapi->enqueueScript('forminput-featuredimage-inner-tmpl', $folder.'forminput-featuredimage-inner.tmpl.js', array('handlebars'), POP_CONTENTCREATIONWEBPLATFORM_VERSION, true);
    }
}
