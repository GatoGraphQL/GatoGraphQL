<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

\PoP\Root\App::getHookManager()->addAction('init', 'setAllcontentPostTypes', 100);
function setAllcontentPostTypes()
{

    // exclude from search results, so they don't appear for function `getAllcontentPostTypes`
    global $wp_post_types;
    $wp_post_types['page']->exclude_from_search = true;
    $wp_post_types['attachment']->exclude_from_search = true;
}
