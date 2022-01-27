<?php

\PoP\Root\App::addFilter('getTheMainCategories', 'getCategorypostsTheMainCategories');
function getCategorypostsTheMainCategories($cats)
{
    return array_merge(
        $cats,
        PoP_CategoryPosts_Utils::getCats()
    );
}
