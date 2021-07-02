<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\FileStore\Facades\FileRendererFacade;
use PoP\ComponentModel\State\ApplicationState;

class PoP_CDN_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-cdn', false, dirname(plugin_basename(__FILE__)).'/languages');

        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        if (!$cmsapplicationapi->isAdminPanel()) {
            HooksAPIFacade::getInstance()->addAction('popcms:enqueueScripts', array($this, 'registerScripts'));
            
            // Inline scripts
            HooksAPIFacade::getInstance()->addAction('popcms:head', array($this, 'printInlineScripts'));
        }

        /**
         * Load the PoP Library
         */
        include_once 'library/load.php';

        /**
         * Load the Plugins
         */
        include_once 'plugins/load.php';
    }

    public function registerScripts()
    {

        // Only if not doing code splitting then load the resources. Otherwise, the resources will be loaded by the ResourceLoader
        if (PoP_WebPlatform_ConfigurationUtils::registerScriptsAndStylesDuringInit()) {
            $cmswebplatformapi = \PoP\EngineWebPlatform\FunctionAPIFactory::getInstance();
            $js_folder = POP_CDN_URL.'/js';
            $dist_js_folder = $js_folder.'/dist';
            $libraries_js_folder = (PoP_WebPlatform_ServerUtils::useMinifiedResources() ? $dist_js_folder : $js_folder).'/libraries';
            $suffix = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? '.min' : '';
            $bundles_js_folder = $dist_js_folder.'/bundles';
        
            if (PoP_WebPlatform_ServerUtils::useBundledResources()) {
                $cmswebplatformapi->registerScript('pop-cdn', $bundles_js_folder . '/pop-cdn.bundle.min.js', array(), POP_CDN_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-cdn');
            } else {
                $cmswebplatformapi->registerScript('pop-cdn-functions', $libraries_js_folder.'/cdn'.$suffix.'.js', array('pop'), POP_CDN_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-cdn-functions');
            }

            // This file is generated dynamically, so it can't be added to any bundle or minified
            // That's why we use popVersion() as its version, so upgrading the website will fetch again this file
            global $pop_cdn_configfile;
            if (PoP_WebPlatform_ServerUtils::loadDynamicallyGeneratedResourceFiles()) {
                $vars = ApplicationState::getVars();
                $cmswebplatformapi->registerScript('pop-cdn-config', $pop_cdn_configfile->getFileurl(), array(), $vars['version'], true);
                $cmswebplatformapi->enqueueScript('pop-cdn-config');
            }
        }
    }

    public function printInlineScripts($scripts)
    {

        // Only if not doing code splitting then load the resources. Otherwise, the resources will be loaded by the ResourceLoader
        if (PoP_WebPlatform_ConfigurationUtils::registerScriptsAndStylesDuringInit()) {
            $cmswebplatformapi = \PoP\EngineWebPlatform\FunctionAPIFactory::getInstance();
            if (!PoP_WebPlatform_ServerUtils::loadDynamicallyGeneratedResourceFiles()) {
                global $pop_cdn_configfile;
                $scripts = FileRendererFacade::getInstance()->render($pop_cdn_configfile);
                printf('<script type="text/javascript">%s</script>', $scripts);
            }
        }

        return $scripts;
    }
}
