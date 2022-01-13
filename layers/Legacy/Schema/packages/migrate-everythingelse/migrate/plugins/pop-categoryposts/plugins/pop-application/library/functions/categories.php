<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

HooksAPIFacade::getInstance()->addFilter('getTheMainCategories', 'getCategorypostsTheMainCategories');
function getCategorypostsTheMainCategories($cats)
{
    return array_merge(
        $cats,
        PoP_CategoryPosts_Utils::getCats()
    );
}
