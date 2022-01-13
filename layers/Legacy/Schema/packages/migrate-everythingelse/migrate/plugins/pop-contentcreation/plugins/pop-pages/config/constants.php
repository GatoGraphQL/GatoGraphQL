<?php

use PoPSchema\Pages\Facades\PageTypeAPIFacade;

\PoP\Root\App::addAction(
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
