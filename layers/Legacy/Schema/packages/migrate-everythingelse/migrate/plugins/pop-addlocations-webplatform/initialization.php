<?php
class PoP_AddLocationsWebPlatform_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-addlocations-webplatform', false, dirname(plugin_basename(__FILE__)).'/languages');

        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        if (!$cmsapplicationapi->isAdminPanel()) {
            \PoP\Root\App::addAction('popcms:enqueueScripts', $this->registerScripts(...));
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
            $js_folder = POP_ADDLOCATIONSWEBPLATFORM_URL.'/js';
            $dist_js_folder = $js_folder.'/dist';
            $libraries_js_folder = (PoP_WebPlatform_ServerUtils::useMinifiedResources() ? $dist_js_folder : $js_folder).'/libraries';
            $suffix = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? '.min' : '';
            $bundles_js_folder = $dist_js_folder.'/bundles';

            if (PoP_WebPlatform_ServerUtils::useBundledResources()) {
                $cmswebplatformapi->registerScript('pop-addlocations-webplatform-templates', $bundles_js_folder . '/pop-addlocations.templates.bundle.min.js', array(), POP_ADDLOCATIONSWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-addlocations-webplatform-templates');

                $cmswebplatformapi->registerScript('pop-addlocations-webplatform', $bundles_js_folder . '/pop-addlocations.bundle.min.js', array('pop', 'jquery'), POP_ADDLOCATIONSWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-addlocations-webplatform');
            } else {
                if (defined('POP_BOOTSTRAPPROCESSORS_INITIALIZED')) {
                    $cmswebplatformapi->registerScript('pop-addlocations-webplatform-bootstrap-createlocation', $libraries_js_folder.'/bootstrap/bootstrap-create-location'.$suffix.'.js', array('jquery', 'pop'), POP_ADDLOCATIONSWEBPLATFORM_VERSION, true);
                    $cmswebplatformapi->enqueueScript('pop-addlocations-webplatform-bootstrap-createlocation');
                }

                // Templates
                $this->enqueueTemplatesScripts();
            }
        }
    }

    public function enqueueTemplatesScripts()
    {
        $cmswebplatformapi = \PoP\EngineWebPlatform\FunctionAPIFactory::getInstance();
        $folder = POP_ADDLOCATIONSWEBPLATFORM_URL.'/js/dist/templates/';

        $cmswebplatformapi->enqueueScript('em-frame-createlocationmap-tmpl', $folder.'em-frame-createlocationmap.tmpl.js', array('handlebars'), POP_ADDLOCATIONSWEBPLATFORM_VERSION, true);
    }
}
