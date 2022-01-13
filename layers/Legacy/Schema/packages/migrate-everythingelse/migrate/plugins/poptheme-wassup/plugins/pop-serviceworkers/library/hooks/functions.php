<?php

// Enable ServiceWorkers to cache all TinyMCE files for the webplatform
\PoP\Root\App::getHookManager()->addFilter('PoP_ServiceWorkers_Hooks_TinyMCE:enable', '__return_true');

// The Wassup Theme color can also be used for the service workers
\PoP\Root\App::getHookManager()->addFilter('PoPTheme_Wassup_ServiceWorkers_Hooks_Manifest:color', 'gdGetThemeColor');
