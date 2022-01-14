<?php

use PoPSchema\Pages\Facades\PageTypeAPIFacade;

\PoP\Root\App::addAction(
    'init', // Must migrate this WP hook to one from PoP (which executes before AFTER_BOOT_APPLICATION
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
