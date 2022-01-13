<?php
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\Root\Facades\Hooks\HooksAPIFacade;

//-------------------------------------------------------------------------------------
// Load Plugin-specific Libraries
//-------------------------------------------------------------------------------------

// Comment Leo 21/10/2017: code below commented because, by default, it doesn't work, since the website-name is currently part of the URL
// (eg: wp-content/pop-content/mesym/...)
// A unifying solution must be found using the discoverability features, through which a website can broadcast all its information, including,
// in this case, the location of its config file
// \PoP\Root\App::getHookManager()->addFilter('gd_jquery_constants', 'gdJqueryConstantsMultidomainCodesplitting');
// function gdJqueryConstantsMultidomainCodesplitting($jqueryConstants) {

//     // Add the placeholder to retrieve the resourceloader-config.js file from external websites
//     if (PoP_ResourceLoader_ServerUtils::useCodeSplitting()) {

//         global $pop_sparesourceloader_configfile;
//         $url = $pop_sparesourceloader_configfile->getFileurl();

//         // Must add the version (request will be routed through CDN)
//         $url = GeneralUtils::addQueryArgs(['ver', ApplicationInfoFacade::getInstance()->getVersion()], $url);

//         // Replace the domain with "{0}" for the external domain to be injected in javascript
//         $cmsService = CMSServiceFacade::getInstance();
//         $url = '{0}'.substr($url, strlen($cmsService->getSiteURL()));
//         $jqueryConstants['CODESPLITTING']['URLPLACEHOLDERS']['CONFIG'] = $url;
//     }
//     return $jqueryConstants;
// }
