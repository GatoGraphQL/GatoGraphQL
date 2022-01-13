<?php

use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoPSchema\Pages\Facades\PageTypeAPIFacade;

HooksAPIFacade::getInstance()->addAction(
    'popcms:init',
    function () {
        if (defined('POP_ADDCOMMENTS_PAGEPLACEHOLDER_SPAMMEDCOMMENTNOTIFICATION')) {
            $pageTypeAPI = PageTypeAPIFacade::getInstance();
            define(
                'POP_ADDCOMMENTS_URLPLACEHOLDER_SPAMMEDCOMMENTNOTIFICATION',
                $pageTypeAPI->getPermalink(\POP_ADDCOMMENTS_PAGEPLACEHOLDER_SPAMMEDCOMMENTNOTIFICATION)
            );
        }
    }
);
