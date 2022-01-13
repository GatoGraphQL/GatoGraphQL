<?php

use PoPSchema\Pages\Facades\PageTypeAPIFacade;

\PoP\Root\App::addAction(
    'popcms:init',
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
