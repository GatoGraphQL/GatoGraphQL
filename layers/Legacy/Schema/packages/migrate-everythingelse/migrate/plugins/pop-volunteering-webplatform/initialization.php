<?php
class PoP_VolunteeringWebPlatform_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-volunteering-webplatform', false, dirname(plugin_basename(__FILE__)).'/languages');

        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        if (!$cmsapplicationapi->isAdminPanel()) {
            // After PoPTheme MESYM
            \PoP\Root\App::addAction('popcms:enqueueScripts', $this->registerScripts(...), 110);
            \PoP\Root\App::addAction('popcms:printStyles', $this->registerStyles(...), 110);
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
            $js_folder = POP_VOLUNTEERINGWEBPLATFORM_URL.'/js';
            $dist_js_folder = $js_folder.'/dist';
            $bundles_js_folder = $dist_js_folder.'/bundles';

            // Load different files depending on the environment (PROD / DEV)
            if (PoP_WebPlatform_ServerUtils::useBundledResources()) {
                // All MESYM Theme Customization Templates
                $cmswebplatformapi->registerScript('pop-volunteering-webplatform-templates', $bundles_js_folder . '/pop-volunteering.templates.bundle.min.js', array(), POP_VOLUNTEERINGWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-volunteering-webplatform-templates');
            } else {

                // Templates
                $this->enqueueTemplatesScripts();
            }
        }
    }

    public function enqueueTemplatesScripts()
    {
        $cmswebplatformapi = \PoP\EngineWebPlatform\FunctionAPIFactory::getInstance();
        $folder = POP_VOLUNTEERINGWEBPLATFORM_URL.'/js/dist/templates/';
    }

    public function registerStyles()
    {

        // Only if not doing code splitting then load the resources. Otherwise, the resources will be loaded by the ResourceLoader
        if (PoP_WebPlatform_ConfigurationUtils::registerScriptsAndStylesDuringInit()) {
            $htmlcssplatformapi = \PoP\EngineHTMLCSSPlatform\FunctionAPIFactory::getInstance();
            $css_folder = POP_VOLUNTEERINGWEBPLATFORM_URL.'/css';
            $dist_css_folder = $css_folder . '/dist';

            if (PoP_WebPlatform_ServerUtils::useBundledResources()) {
                $bundles_css_folder = $dist_css_folder . '/bundles';

                $htmlcssplatformapi->registerStyle('pop-volunteering-webplatform', $bundles_css_folder . '/pop-volunteering.bundle.min.css', array('bootstrap'), POP_VOLUNTEERINGWEBPLATFORM_VERSION);
                $htmlcssplatformapi->enqueueStyle('pop-volunteering-webplatform');
            } else {
                $libraries_css_folder = (PoP_WebPlatform_ServerUtils::useMinifiedResources() ? $dist_css_folder : $css_folder).'/libraries';
                $suffix = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? '.min' : '';
            }
        }
    }
}
