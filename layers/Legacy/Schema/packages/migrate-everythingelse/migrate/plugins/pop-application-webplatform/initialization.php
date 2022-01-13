<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
class PoP_ApplicationWebPlatform_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-application-webplatform', false, dirname(plugin_basename(__FILE__)).'/languages');

        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        if (!$cmsapplicationapi->isAdminPanel()) {
            // After PoP
            HooksAPIFacade::getInstance()->addAction('popcms:enqueueScripts', array($this, 'registerScripts'), 100);
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
            $js_folder = POP_APPLICATIONWEBPLATFORM_URL.'/js';
            $dist_js_folder = $js_folder.'/dist';
            $libraries_js_folder = (PoP_WebPlatform_ServerUtils::useMinifiedResources() ? $dist_js_folder : $js_folder).'/libraries';
            $suffix = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? '.min' : '';
            $bundles_js_folder = $dist_js_folder.'/bundles';

            // Load different files depending on the environment (PROD / DEV)
            if (PoP_WebPlatform_ServerUtils::useBundledResources()) {
                // All MESYM Theme Customization Templates
                $cmswebplatformapi->registerScript('pop-application-webplatform-templates', $bundles_js_folder . '/pop-application.templates.bundle.min.js', array(), POPTHEME_WASSUP_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-application-webplatform-templates');
            } else {

                // Templates
                $this->enqueueTemplatesScripts();
            }
        }
    }

    public function enqueueTemplatesScripts()
    {
        $cmswebplatformapi = \PoP\EngineWebPlatform\FunctionAPIFactory::getInstance();
        $folder = POP_APPLICATIONWEBPLATFORM_URL.'/js/dist/templates/';

        $cmswebplatformapi->enqueueScript('layout-volunteertag-tmpl', $folder.'layout-volunteertag.tmpl.js', array('handlebars'), POPTHEME_WASSUP_VERSION, true);
        $cmswebplatformapi->enqueueScript('layout-link-access-tmpl', $folder.'layout-link-access.tmpl.js', array('handlebars'), POPTHEME_WASSUP_VERSION, true);
        $cmswebplatformapi->enqueueScript('speechbubble-tmpl', $folder.'speechbubble.tmpl.js', array('handlebars'), POPTHEME_WASSUP_VERSION, true);
    }
}
