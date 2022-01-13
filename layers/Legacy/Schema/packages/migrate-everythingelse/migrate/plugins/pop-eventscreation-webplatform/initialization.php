<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
class PoP_EventsCreationWebPlatform_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-eventscreation-webplatform', false, dirname(plugin_basename(__FILE__)).'/languages');

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

        // Only if not doing code splitting then load the resources. Otherwise, the resources will be loaded by the ResourceLoader
        if (PoP_WebPlatform_ConfigurationUtils::registerScriptsAndStylesDuringInit()) {
            $cmswebplatformapi = \PoP\EngineWebPlatform\FunctionAPIFactory::getInstance();
            $js_folder = POP_EVENTSCREATIONWEBPLATFORM_URL.'/js';
            $dist_js_folder = $js_folder.'/dist';
            $libraries_js_folder = (PoP_WebPlatform_ServerUtils::useMinifiedResources() ? $dist_js_folder : $js_folder).'/libraries';
            $suffix = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? '.min' : '';
            $bundles_js_folder = $dist_js_folder.'/bundles';

            if (PoP_WebPlatform_ServerUtils::useBundledResources()) {
                $cmswebplatformapi->registerScript('pop-eventscreation-webplatform-templates', $bundles_js_folder . '/pop-eventscreation.templates.bundle.min.js', array(), POP_EVENTSCREATIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-eventscreation-webplatform-templates');
            } else {

                // Templates
                $this->enqueueTemplatesScripts();
            }
        }
    }

    public function enqueueTemplatesScripts()
    {
        $cmswebplatformapi = \PoP\EngineWebPlatform\FunctionAPIFactory::getInstance();
        $folder = POP_EVENTSCREATIONWEBPLATFORM_URL.'/js/dist/templates/';

        $cmswebplatformapi->enqueueScript('em-layoutevent-tablecol-tmpl', $folder.'em-layoutevent-tablecol.tmpl.js', array('handlebars'), POP_EVENTSCREATIONWEBPLATFORM_VERSION, true);
    }
}
