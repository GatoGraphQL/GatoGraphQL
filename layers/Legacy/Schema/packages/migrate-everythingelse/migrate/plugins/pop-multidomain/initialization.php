<?php
use PoP\ComponentModel\Facades\Info\ApplicationInfoFacade;
use PoP\ComponentModel\State\ApplicationState;
use PoP\FileStore\Facades\FileRendererFacade;

class PoP_MultiDomain_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-multidomain', false, dirname(plugin_basename(__FILE__)).'/languages');

        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        if (!$cmsapplicationapi->isAdminPanel()) {
            \PoP\Root\App::addAction('popcms:enqueueScripts', array($this, 'registerScripts'));
            
            // Inline scripts
            \PoP\Root\App::addAction('popcms:head', array($this, 'printInlineScripts'));
        }

        /**
         * Constants/Configuration for functionalities needed by the plug-in
         */
        include_once 'config/load.php';

        /**
         * Load the PoP Library
         */
        include_once 'pop-library/load.php';

        /**
         * Load the Library
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
            $js_folder = POP_MULTIDOMAIN_URL.'/js';
            $dist_js_folder = $js_folder.'/dist';
            $libraries_js_folder = (PoP_WebPlatform_ServerUtils::useMinifiedResources() ? $dist_js_folder : $js_folder).'/libraries';
            $suffix = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? '.min' : '';
            $bundles_js_folder = $dist_js_folder.'/bundles';

            if (PoP_WebPlatform_ServerUtils::useBundledResources()) {
                $cmswebplatformapi->registerScript('pop-multidomain', $bundles_js_folder . '/pop-multidomain.bundle.min.js', array(), POP_MULTIDOMAIN_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-multidomain');
            } else {
                $cmswebplatformapi->registerScript('pop-multidomain-functions', $libraries_js_folder.'/multidomain'.$suffix.'.js', array('pop'), POP_MULTIDOMAIN_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-multidomain-functions');
            }

            // This file is generated dynamically, so it can't be added to any bundle or minified
            // That's why we use popVersion() as its version, so upgrading the website will fetch again this file
            global $pop_multidomain_initdomainscripts_configfile;
            if (PoP_WebPlatform_ServerUtils::loadDynamicallyGeneratedResourceFiles()) {
                $cmswebplatformapi->registerScript('pop-multidomain-domainscripts', $pop_multidomain_initdomainscripts_configfile->getFileurl(), array(), ApplicationInfoFacade::getInstance()->getVersion());
                $cmswebplatformapi->enqueueScript('pop-multidomain-domainscripts');
            }
        }


        // No need to declare this file here, since if not doing code splitting, then the resourceLoader config file from MultiDomain is not needed
        // // if (PoP_ResourceLoader_ServerUtils::useCodeSplitting()) {
        // else {

        //     // This file is generated dynamically, so it can't be added to any bundle or minified
        //     // That's why we use popVersion() as its version, so upgrading the website will fetch again this file
        //     global $pop_multidomain_resourceloader_configfile;
        //     $cmswebplatformapi->registerScript('pop-multidomain-sparesourceloader-config', $pop_multidomain_resourceloader_configfile->getFileurl(), array(PoP_ResourceLoaderProcessorUtils::getNoconflictResourceName([PoP_MultiDomain_JSResourceLoaderProcessor::class, PoP_MultiDomain_JSResourceLoaderProcessor::RESOURCE_MULTIDOMAIN])), ApplicationInfoFacade::getInstance()->getVersion(), true);
        //     $cmswebplatformapi->enqueueScript('pop-multidomain-sparesourceloader-config');
        // }
        // Same for multidomain-resourceloader.js
    }

    public function printInlineScripts($scripts)
    {

        // Only if not doing code splitting then load the resources. Otherwise, the resources will be loaded by the ResourceLoader
        if (PoP_WebPlatform_ConfigurationUtils::registerScriptsAndStylesDuringInit()) {
            if (!PoP_WebPlatform_ServerUtils::loadDynamicallyGeneratedResourceFiles()) {
                global $pop_multidomain_initdomainscripts_configfile;
                $scripts = FileRendererFacade::getInstance()->render($pop_multidomain_initdomainscripts_configfile);
                printf('<script type="text/javascript">%s</script>', $scripts);
            }
        }

        return $scripts;
    }
}
