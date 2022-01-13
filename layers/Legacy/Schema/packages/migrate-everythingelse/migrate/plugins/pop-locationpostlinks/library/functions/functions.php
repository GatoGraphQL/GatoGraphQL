<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

HooksAPIFacade::getInstance()->addFilter('gd_thumb_defaultlink:link_categories', 'popLocationpostlinksMainLinkcategories');
function popLocationpostlinksMainLinkcategories($cats)
{

    // The following categories can never be the main one
    return array_merge(
        $cats,
        array_filter(
            array(
                POP_LOCATIONPOSTLINKS_CAT_LOCATIONPOSTLINKS,
            )
        )
    );
}
