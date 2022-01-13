<?php

/**
 * CDN URLs
 */

// Load the dependencies assets from the CDN
\PoP\Root\App::getHookManager()->addFilter('PoP_ServiceWorkers_Job:dependencies_path', 'popCdnfoundationAssetsrc');
