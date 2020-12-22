<?php
use PoP\Hooks\Facades\HooksAPIFacade;

/**
 * CDN URLs
 */

// Load the dependencies assets from the CDN
HooksAPIFacade::getInstance()->addFilter('PoP_ServiceWorkers_Job:dependencies_path', 'popCdnfoundationAssetsrc');
