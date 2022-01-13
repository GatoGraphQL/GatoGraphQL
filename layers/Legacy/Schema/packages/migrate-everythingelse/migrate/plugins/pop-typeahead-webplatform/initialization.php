<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
class PoP_TypeaheadWebPlatform_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-typeahead-webplatform', false, dirname(plugin_basename(__FILE__)).'/languages');

        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        if (!$cmsapplicationapi->isAdminPanel()) {
            HooksAPIFacade::getInstance()->addAction('popcms:enqueueScripts', array($this, 'registerScripts'));
            HooksAPIFacade::getInstance()->addAction('popcms:printStyles', array($this, 'registerStyles'));
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
            $js_folder = POP_TYPEAHEADWEBPLATFORM_URL.'/js';
            $includes_js_folder = $js_folder.'/includes';
            $cdn_js_folder = $includes_js_folder . '/cdn';
            $dist_js_folder = $js_folder.'/dist';
            $libraries_js_folder = (PoP_WebPlatform_ServerUtils::useMinifiedResources() ? $dist_js_folder : $js_folder).'/libraries';
            $suffix = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? '.min' : '';
            $bundles_js_folder = $dist_js_folder.'/bundles';

            if (PoP_WebPlatform_ServerUtils::useBundledResources()) {
                $cmswebplatformapi->registerScript('pop-typeahead-webplatform-templates', $bundles_js_folder . '/pop-typeahead.templates.bundle.min.js', array(), POP_TYPEAHEADWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-typeahead-webplatform-templates');
                
                $cmswebplatformapi->registerScript('pop-typeahead-webplatform', $bundles_js_folder . '/pop-typeahead.bundle.min.js', array(), POP_TYPEAHEADWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-typeahead-webplatform');
            } else {

                // Templates
                $this->enqueueTemplatesScripts();
            }
        }
    }

    public function enqueueTemplatesScripts()
    {
        $cmswebplatformapi = \PoP\EngineWebPlatform\FunctionAPIFactory::getInstance();
        $folder = POP_TYPEAHEADWEBPLATFORM_URL.'/js/dist/templates/';
    }
    public function registerStyles()
    {

        // Only if not doing code splitting then load the resources. Otherwise, the resources will be loaded by the ResourceLoader
        if (PoP_WebPlatform_ConfigurationUtils::registerScriptsAndStylesDuringInit()) {
            $cmswebplatformapi = \PoP\EngineWebPlatform\FunctionAPIFactory::getInstance();
            $css_folder = POP_TYPEAHEADWEBPLATFORM_URL.'/css';
            $includes_css_folder = $css_folder . '/includes';
            $cdn_css_folder = $includes_css_folder . '/cdn';
        }
    }
}
