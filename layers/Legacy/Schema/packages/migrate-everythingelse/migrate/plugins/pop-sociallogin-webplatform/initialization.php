<?php
class PoP_SocialLoginWebPlatform_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-sociallogin-webplatform', false, dirname(plugin_basename(__FILE__)).'/languages');

        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        if (!$cmsapplicationapi->isAdminPanel()) {
            \PoP\Root\App::getHookManager()->addAction("popcms:enqueueScripts", array($this, 'registerScripts'));
        }

        /**
         * PoP Library
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
            $js_folder = POP_SOCIALLOGINWEBPLATFORM_URL.'/js';
            $dist_js_folder = $js_folder.'/dist';
            $libraries_js_folder = (PoP_WebPlatform_ServerUtils::useMinifiedResources() ? $dist_js_folder : $js_folder).'/libraries';
            $suffix = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? '.min' : '';
            $bundles_js_folder = $dist_js_folder.'/bundles';

            if (PoP_WebPlatform_ServerUtils::useBundledResources()) {
                $cmswebplatformapi->registerScript('pop-sociallogin-webplatform-templates', $bundles_js_folder . '/pop-sociallogin.templates.bundle.min.js', array(), POP_SOCIALLOGINWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-sociallogin-webplatform-templates');

                $cmswebplatformapi->registerScript('pop-sociallogin-webplatform', $bundles_js_folder . '/pop-sociallogin.bundle.min.js', array('pop', 'jquery'), POP_SOCIALLOGINWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-sociallogin-webplatform');
            } else {
                $cmswebplatformapi->registerScript('pop-sociallogin-webplatform-functions', $libraries_js_folder.'/sociallogin-functions'.$suffix.'.js', array('jquery', 'pop'), POP_SOCIALLOGINWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-sociallogin-webplatform-functions');

                // Templates
                $this->enqueueTemplatesScripts();
            }
        }
    }

    public function enqueueTemplatesScripts()
    {
        $cmswebplatformapi = \PoP\EngineWebPlatform\FunctionAPIFactory::getInstance();
        $folder = POP_SOCIALLOGINWEBPLATFORM_URL.'/js/dist/templates/';

        $cmswebplatformapi->enqueueScript('sociallogin-networklinks-tmpl', $folder.'sociallogin-networklinks.tmpl.js', array('handlebars'), POP_SOCIALLOGINWEBPLATFORM_VERSION, true);
    }
}
