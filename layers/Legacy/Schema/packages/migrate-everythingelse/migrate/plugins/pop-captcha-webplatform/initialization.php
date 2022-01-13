<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
class PoP_CaptchaWebPlatform_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-captcha-webplatform', false, dirname(plugin_basename(__FILE__)).'/languages');

        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        if (!$cmsapplicationapi->isAdminPanel()) {
            HooksAPIFacade::getInstance()->addAction('popcms:enqueueScripts', array($this, 'registerScripts'));
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
            $js_folder = POP_CAPTCHAWEBPLATFORM_URL.'/js';
            $dist_js_folder = $js_folder.'/dist';
            $libraries_js_folder = (PoP_WebPlatform_ServerUtils::useMinifiedResources() ? $dist_js_folder : $js_folder).'/libraries';
            $suffix = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? '.min' : '';
            $bundles_js_folder = $dist_js_folder.'/bundles';

            if (PoP_WebPlatform_ServerUtils::useBundledResources()) {
                $cmswebplatformapi->registerScript('pop-captcha-webplatform-templates', $bundles_js_folder . '/pop-captcha-webplatform.templates.bundle.min.js', array(), POP_CAPTCHAWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-captcha-webplatform-templates');
            } else {

                // Templates
                $this->enqueueTemplatesScripts();
            }
        }
    }

    public function enqueueTemplatesScripts()
    {
        $cmswebplatformapi = \PoP\EngineWebPlatform\FunctionAPIFactory::getInstance();
        $folder = POP_CAPTCHAWEBPLATFORM_URL.'/js/dist/templates/';
        
        $cmswebplatformapi->enqueueScript('forminput-captcha-tmpl', $folder.'forminput-captcha.tmpl.js', array('handlebars'), POP_CAPTCHAWEBPLATFORM_VERSION, true);
    }
}
