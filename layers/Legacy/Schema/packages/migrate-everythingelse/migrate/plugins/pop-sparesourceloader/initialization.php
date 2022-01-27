<?php

class PoP_SPAResourceLoader_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-sparesourceloader', false, dirname(plugin_basename(__FILE__)).'/languages');

        // $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        // if (!$cmsapplicationapi->isAdminPanel()) {

        //     \PoP\Root\App::addAction('popcms:enqueueScripts', array($this, 'registerScripts'));
        // }

        /**
         * Load the Kernel
         */
        include_once 'kernel/load.php';

        /**
         * Load the Library
         */
        include_once 'library/load.php';

        /**
         * Load the Plug-ins Library
         */
        include_once 'plugins/load.php';
    }

    // function registerScripts() {

    //     // Only if not doing code splitting then load the resources. Otherwise, the resources will be loaded by the SPAResourceLoader
    //     if (PoP_WebPlatform_ConfigurationUtils::registerScriptsAndStylesDuringInit()) {
            // $cmswebplatformapi = \PoP\EngineWebPlatform\FunctionAPIFactory::getInstance();

    //         $js_folder = POP_SPARESOURCELOADER_URL.'/js';
    //         $dist_js_folder = $js_folder.'/dist';
    //         $libraries_js_folder = (PoP_WebPlatform_ServerUtils::useMinifiedResources() ? $dist_js_folder : $js_folder).'/libraries';
    //         $suffix = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? '.min' : '';
    //         $bundles_js_folder = $dist_js_folder.'/bundles';

    //         if (PoP_WebPlatform_ServerUtils::useBundledResources()) {

    //             $cmswebplatformapi->registerScript('pop-sparesourceloader', $bundles_js_folder . '/pop-sparesourceloader.bundle.min.js', array(), POP_SPARESOURCELOADER_VERSION, true);
    //             $cmswebplatformapi->enqueueScript('pop-sparesourceloader');
    //         }
    //         else {

    //         }
    //     }

    // Same, no need to declare file "handlebarshelpers-resourceloader-hooks.js"

    //     // No need to declare this file here, since it's already defined in the sparesourceloader-processor
    //     // Also, if not doing code splitting, then no need for the resourceLoader config file
    //     // if (PoP_ResourceLoader_ServerUtils::useCodeSplitting()) {

    //     //     $cmswebplatformapi->registerScript('pop-codesplit-jslibrary-manager', $libraries_js_folder.'/codesplit-jslibrary-manager'.$suffix.'.js', array('jquery'), POP_SPARESOURCELOADER_VERSION, true);
    //     //     $cmswebplatformapi->enqueueScript('pop-codesplit-jslibrary-manager');
    //     // }

    //     // No need to declare this file here, since it's already defined in the sparesourceloader-processor
    //     // Also, if not doing code splitting, then no need for the resourceLoader config file
    //     // // if (PoP_ResourceLoader_ServerUtils::useCodeSplitting()) {
    //     // else {

    //     //     // This file is generated dynamically, so it can't be added to any bundle or minified
    //     //     // That's why we use popVersion() as its version, so upgrading the website will fetch again this file
    //     //     global $pop_sparesourceloader_configfile;
    //     //     $cmswebplatformapi->registerScript('pop-sparesourceloader-config', $pop_sparesourceloader_configfile->getFileurl(), array(PoP_SPAResourceLoaderProcessorUtils::getNoconflictResourceName(POP_SPARESOURCELOADER_SPARESOURCELOADER)), ApplicationInfoFacade::getInstance()->getVersion(), true);
    //     //     $cmswebplatformapi->enqueueScript('pop-sparesourceloader-config');
    //     // }
    // }
}
