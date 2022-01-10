<?php
use PoP\ComponentModel\Facades\Info\ApplicationInfoFacade;
use PoP\ComponentModel\State\ApplicationState;
use PoP\FileStore\Facades\FileRendererFacade;
use PoP\Hooks\Facades\HooksAPIFacade;

class PoP_UserLoginWebPlatform_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-userlogin-webplatform', false, dirname(plugin_basename(__FILE__)).'/languages');

        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        if (!$cmsapplicationapi->isAdminPanel()) {
            HooksAPIFacade::getInstance()->addAction('popcms:enqueueScripts', array($this, 'registerScripts'));
            HooksAPIFacade::getInstance()->addAction('popcms:printStyles', array($this, 'registerStyles'), 100);

            // Inline styles
            HooksAPIFacade::getInstance()->addAction('popcms:head', array($this, 'printInlineStyles'));
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
            $js_folder = POP_USERLOGINWEBPLATFORM_URL.'/js';
            $dist_js_folder = $js_folder.'/dist';
            $libraries_js_folder = (PoP_WebPlatform_ServerUtils::useMinifiedResources() ? $dist_js_folder : $js_folder).'/libraries';
            $suffix = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? '.min' : '';
            $bundles_js_folder = $dist_js_folder.'/bundles';

            if (PoP_WebPlatform_ServerUtils::useBundledResources()) {
                $cmswebplatformapi->registerScript('pop-userlogin-webplatform', $bundles_js_folder . '/pop-userlogin.bundle.min.js', array(), POP_USERLOGINWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-userlogin-webplatform');
                
                $cmswebplatformapi->registerScript('pop-userlogin-webplatform-templates', $bundles_js_folder . '/pop-userlogin.templates.bundle.min.js', array(), POP_USERLOGINWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-userlogin-webplatform-templates');
            } else {
                $cmswebplatformapi->registerScript('pop-userloginwebplatform-user-account', $libraries_js_folder.'/user-account'.$suffix.'.js', array('jquery', 'pop'), POP_USERLOGINWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-userloginwebplatform-user-account');

                // Templates
                $this->enqueueTemplatesScripts();
            }
        }
    }

    public function enqueueTemplatesScripts()
    {
        $cmswebplatformapi = \PoP\EngineWebPlatform\FunctionAPIFactory::getInstance();
        $folder = POP_USERLOGINWEBPLATFORM_URL.'/js/dist/templates/';

        $cmswebplatformapi->enqueueScript('userloggedin-tmpl', $folder.'userloggedin.tmpl.js', array('handlebars'), POP_USERLOGINWEBPLATFORM_VERSION, true);
    }

    public function registerStyles()
    {

        // Only if not doing code splitting then load the resources. Otherwise, the resources will be loaded by the ResourceLoader
        if (PoP_WebPlatform_ConfigurationUtils::registerScriptsAndStylesDuringInit()) {
            $htmlcssplatformapi = \PoP\EngineHTMLCSSPlatform\FunctionAPIFactory::getInstance();
            // This file is generated dynamically, so it can't be added to any bundle or minified
            // That's why we use popVersion() as its version, so upgrading the website will fetch again this file
            global $popcore_userloggedinstyles_file;
            if (PoP_WebPlatform_ServerUtils::loadDynamicallyGeneratedResourceFiles()) {
                $vars = ApplicationState::getVars();
                $htmlcssplatformapi->registerStyle('pop-userlogin-webplatform-userloggedin', $popcore_userloggedinstyles_file->getFileurl(), array(), ApplicationInfoFacade::getInstance()->getVersion());
                $htmlcssplatformapi->enqueueStyle('pop-userlogin-webplatform-userloggedin');
            }
        }
    }

    public function printInlineStyles($styles)
    {

        // Only if not doing code splitting then load the resources. Otherwise, the resources will be loaded by the ResourceLoader
        if (PoP_WebPlatform_ConfigurationUtils::registerScriptsAndStylesDuringInit()) {
            if (!PoP_WebPlatform_ServerUtils::loadDynamicallyGeneratedResourceFiles()) {
                global $popcore_userloggedinstyles_file;
                $styles = FileRendererFacade::getInstance()->render($popcore_userloggedinstyles_file);
                printf('<style type="text/css">%s</style>', $styles);
            }
        }

        return $styles;
    }
}
