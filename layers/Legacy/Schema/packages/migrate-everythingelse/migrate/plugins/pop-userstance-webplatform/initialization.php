<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
class PoP_UserStanceWebPlatform_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-userstance-webplatform', false, dirname(plugin_basename(__FILE__)).'/languages');

        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        if (!$cmsapplicationapi->isAdminPanel()) {
            // After PoPTheme MESYM
            HooksAPIFacade::getInstance()->addAction('popcms:enqueueScripts', array($this, 'registerScripts'), 110);
            HooksAPIFacade::getInstance()->addAction('popcms:printStyles', array($this, 'registerStyles'), 110);
        }

        /**
         * Load the PoP Library
         */
        include_once 'library/load.php';

        /**
         * Load the Plugins libraries
         */
        include_once 'plugins/load.php';
    }

    public function registerScripts()
    {

        // Only if not doing code splitting then load the resources. Otherwise, the resources will be loaded by the ResourceLoader
        if (PoP_WebPlatform_ConfigurationUtils::registerScriptsAndStylesDuringInit()) {
            $cmswebplatformapi = \PoP\EngineWebPlatform\FunctionAPIFactory::getInstance();
            $js_folder = POP_USERSTANCEWEBPLATFORM_URL.'/js';
            $dist_js_folder = $js_folder.'/dist';
            $bundles_js_folder = $dist_js_folder.'/bundles';

            // Load different files depending on the environment (PROD / DEV)
            if (PoP_WebPlatform_ServerUtils::useBundledResources()) {
                // All MESYM Theme Customization Templates
                $cmswebplatformapi->registerScript('pop-userstance-webplatform-templates', $bundles_js_folder . '/pop-userstance.templates.bundle.min.js', array(), POP_USERSTANCEWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-userstance-webplatform-templates');
            } else {

                // Templates
                $this->enqueueTemplatesScripts();
            }
        }
    }

    public function enqueueTemplatesScripts()
    {
        $cmswebplatformapi = \PoP\EngineWebPlatform\FunctionAPIFactory::getInstance();
        $folder = POP_USERSTANCEWEBPLATFORM_URL.'/js/dist/templates/';

        // All Custom Templates
        $cmswebplatformapi->enqueueScript('layout-stance-tmpl', $folder.'layout-stance.tmpl.js', array('handlebars'), POP_USERSTANCEWEBPLATFORM_VERSION, true);
    }

    public function registerStyles()
    {

        // Only if not doing code splitting then load the resources. Otherwise, the resources will be loaded by the ResourceLoader
        if (PoP_WebPlatform_ConfigurationUtils::registerScriptsAndStylesDuringInit()) {
            $htmlcssplatformapi = \PoP\EngineHTMLCSSPlatform\FunctionAPIFactory::getInstance();
            $css_folder = POP_USERSTANCEWEBPLATFORM_URL.'/css';
            $dist_css_folder = $css_folder . '/dist';

            if (PoP_WebPlatform_ServerUtils::useBundledResources()) {
                $bundles_css_folder = $dist_css_folder . '/bundles';

                $htmlcssplatformapi->registerStyle('pop-userstance-webplatform', $bundles_css_folder . '/pop-userstance.bundle.min.css', array('bootstrap'), POP_USERSTANCEWEBPLATFORM_VERSION);
                $htmlcssplatformapi->enqueueStyle('pop-userstance-webplatform');
            } else {
                $libraries_css_folder = (PoP_WebPlatform_ServerUtils::useMinifiedResources() ? $dist_css_folder : $css_folder).'/libraries';
                $suffix = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? '.min' : '';

                $htmlcssplatformapi->registerStyle('pop-userstance-webplatform', $libraries_css_folder.'/style'.$suffix.'.css', array('bootstrap'), POP_USERSTANCEWEBPLATFORM_VERSION);
                $htmlcssplatformapi->enqueueStyle('pop-userstance-webplatform');

                $htmlcssplatformapi->registerStyle('pop-userstance-webplatform-layout', $libraries_css_folder.'/voting-layout'.$suffix.'.css', array('bootstrap'), POP_USERSTANCEWEBPLATFORM_VERSION);
                $htmlcssplatformapi->enqueueStyle('pop-userstance-webplatform-layout');
            }
        }
    }
}
