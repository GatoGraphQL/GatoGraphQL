<?php
class PoP_CommonUserRolesWebPlatform_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-commonuserroles-webplatform', false, dirname(plugin_basename(__FILE__)).'/languages');

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
            $js_folder = POP_COMMONUSERROLESWEBPLATFORM_URL.'/js';
            $dist_js_folder = $js_folder.'/dist';
            $libraries_js_folder = (PoP_WebPlatform_ServerUtils::useMinifiedResources() ? $dist_js_folder : $js_folder).'/libraries';
            $suffix = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? '.min' : '';
            $bundles_js_folder = $dist_js_folder.'/bundles';

            if (PoP_WebPlatform_ServerUtils::useBundledResources()) {
                $cmswebplatformapi->registerScript('pop-commonuserroles-webplatform-templates', $bundles_js_folder . '/pop-commonuserroles.templates.bundle.min.js', array(), POP_COMMONUSERROLESWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-commonuserroles-webplatform-templates');
            } else {

                // Templates
                $this->enqueueTemplatesScripts();
            }
        }
    }

    public function enqueueTemplatesScripts()
    {
        $cmswebplatformapi = \PoP\EngineWebPlatform\FunctionAPIFactory::getInstance();
        $folder = POP_COMMONUSERROLESWEBPLATFORM_URL.'/js/dist/templates/';

        $cmswebplatformapi->enqueueScript('layoutuser-profileorganization-details-tmpl', $folder.'layoutuser-profileorganization-details.tmpl.js', array('handlebars'), POP_COMMONUSERROLESWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('layoutuser-profileindividual-details-tmpl', $folder.'layoutuser-profileindividual-details.tmpl.js', array('handlebars'), POP_COMMONUSERROLESWEBPLATFORM_VERSION, true);
    }
}
