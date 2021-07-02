<?php
use PoP\Engine\Facades\CMS\CMSServiceFacade;
use PoP\Hooks\Facades\HooksAPIFacade;

// Priority: execute just after the "website-environment" plugin has set all the environment constants
// That is needed to set POP_CDNFOUNDATION_CDN_ASSETS_URI before
HooksAPIFacade::getInstance()->addAction('plugins_loaded', function() {
	    // Use the assets url instead of the site url for all the scripts and styles
		$cmsService = CMSServiceFacade::getInstance();
	    if (POP_CDNFOUNDATION_CDN_ASSETS_URI && (POP_CDNFOUNDATION_CDN_ASSETS_URI != $cmsService->getSiteURL())) {
	        HooksAPIFacade::getInstance()->addFilter('includes_url', 'popCdnfoundationAssetsrc');
	        HooksAPIFacade::getInstance()->addFilter('plugins_url', 'popCdnfoundationAssetsrc');
	        HooksAPIFacade::getInstance()->addFilter('stylesheet_directory_uri', 'popCdnfoundationAssetsrc');
	    }
	},
	88830
);
