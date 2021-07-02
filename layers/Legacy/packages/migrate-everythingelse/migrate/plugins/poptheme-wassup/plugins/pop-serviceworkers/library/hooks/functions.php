<?php
use PoP\Hooks\Facades\HooksAPIFacade;

// Enable ServiceWorkers to cache all TinyMCE files for the webplatform
HooksAPIFacade::getInstance()->addFilter('PoP_ServiceWorkers_Hooks_TinyMCE:enable', '__return_true');

// The Wassup Theme color can also be used for the service workers
HooksAPIFacade::getInstance()->addFilter('PoPTheme_Wassup_ServiceWorkers_Hooks_Manifest:color', 'gdGetThemeColor');
