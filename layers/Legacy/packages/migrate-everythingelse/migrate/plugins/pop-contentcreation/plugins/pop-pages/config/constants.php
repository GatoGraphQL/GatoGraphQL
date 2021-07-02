<?php

use PoP\Hooks\Facades\HooksAPIFacade;
use PoPSchema\Pages\Facades\PageTypeAPIFacade;

HooksAPIFacade::getInstance()->addAction(
    'popcms:init',
    function () {
        if (defined('POP_CONTENTCREATION_PAGEPLACEHOLDER_SPAMMEDPOSTNOTIFICATION')) {
            $pageTypeAPI = PageTypeAPIFacade::getInstance();
            define(
                'POP_CONTENTCREATION_URLPLACEHOLDER_SPAMMEDPOSTNOTIFICATION',
                $pageTypeAPI->getPermalink(\POP_CONTENTCREATION_PAGEPLACEHOLDER_SPAMMEDPOSTNOTIFICATION)
            );
        }
    }
);
