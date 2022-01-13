<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

\PoP\Root\App::getHookManager()->addFilter('gd_thumb_defaultlink:link_categories', 'popEventlinksAddEventlinkCategory');
function popEventlinksAddEventlinkCategory($categories)
{

    // Function also needed to hook categories into PoP_Events_ResourceLoader_Hooks
    if (defined('POP_EVENTLINKS_CAT_EVENTLINKS') && POP_EVENTLINKS_CAT_EVENTLINKS) {
        $categories[] = POP_EVENTLINKS_CAT_EVENTLINKS;
    }

    return $categories;
}
