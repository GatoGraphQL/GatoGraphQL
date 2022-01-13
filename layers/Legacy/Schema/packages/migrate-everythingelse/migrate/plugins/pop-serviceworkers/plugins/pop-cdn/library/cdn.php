<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

/**
 * CDN URLs
 */

// Load the dependencies assets from the CDN
HooksAPIFacade::getInstance()->addFilter('PoP_ServiceWorkers_Job:dependencies_path', 'popCdnfoundationAssetsrc');
