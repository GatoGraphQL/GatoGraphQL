<?php
class PoP_MultiDomainSPAResourceLoader_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-multidomain-sparesourceloader', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the Library
         */
        include_once 'library/load.php';

        /**
         * Load the Plugins Library
         */
        include_once 'plugins/load.php';
    }

    // No need to declare this file here, since if not doing code splitting, then the resourceLoader config file from MultiDomain is not needed
    // // if (PoP_ResourceLoader_ServerUtils::useCodeSplitting()) {
    // else {

    //     // This file is generated dynamically, so it can't be added to any bundle or minified
    //     // That's why we use popVersion() as its version, so upgrading the website will fetch again this file
    //     $cmswebplatformapi = \PoP\EngineWebPlatform\FunctionAPIFactory::getInstance();
    //     global $pop_multidomain_resourceloader_configfile;
    //     $cmswebplatformapi->registerScript('pop-multidomain-sparesourceloader-config', $pop_multidomain_resourceloader_configfile->getFileurl(), array(PoP_ResourceLoaderProcessorUtils::getNoconflictResourceName([PoP_MultiDomain_JSResourceLoaderProcessor::class, PoP_MultiDomain_JSResourceLoaderProcessor::RESOURCE_MULTIDOMAIN])), ApplicationInfoFacade::getInstance()->getVersion(), true);
    //     $cmswebplatformapi->enqueueScript('pop-multidomain-sparesourceloader-config');
    // }
    // Same for multidomain-resourceloader.js
}
