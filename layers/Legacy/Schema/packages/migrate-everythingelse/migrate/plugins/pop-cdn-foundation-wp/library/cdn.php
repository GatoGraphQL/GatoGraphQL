<?php
use PoPCMSSchema\SchemaCommons\Facades\CMS\CMSServiceFacade;

// Priority: execute just after the "website-environment" plugin has set all the environment constants
// That is needed to set POP_CDNFOUNDATION_CDN_ASSETS_URI before
\PoP\Root\App::addAction('plugins_loaded', function() {
	    // Use the assets url instead of the site url for all the scripts and styles
		$cmsService = CMSServiceFacade::getInstance();
	    if (POP_CDNFOUNDATION_CDN_ASSETS_URI && (POP_CDNFOUNDATION_CDN_ASSETS_URI != $cmsService->getSiteURL())) {
	        \PoP\Root\App::addFilter('includes_url', popCdnfoundationAssetsrc(...));
	        \PoP\Root\App::addFilter('plugins_url', popCdnfoundationAssetsrc(...));
	        \PoP\Root\App::addFilter('stylesheet_directory_uri', popCdnfoundationAssetsrc(...));
	    }
	},
	88830
);
