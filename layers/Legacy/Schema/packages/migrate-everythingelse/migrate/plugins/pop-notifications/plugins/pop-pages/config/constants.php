<?php

use PoPSchema\Pages\Facades\PageTypeAPIFacade;

\PoP\Root\App::addAction(
    'init', // Must migrate this WP hook to one from PoP (which executes before AFTER_BOOT_APPLICATION
    function () {
        if (defined('POP_NOTIFICATIONS_PAGEPLACEHOLDER_USERWELCOME')) {
            $pageTypeAPI = PageTypeAPIFacade::getInstance();
            define(
                'POP_NOTIFICATIONS_URLPLACEHOLDER_USERWELCOME',
                $pageTypeAPI->getPermalink(\POP_NOTIFICATIONS_PAGEPLACEHOLDER_USERWELCOME)
            );
        }
    }
);
