<?php

\PoP\Root\App::addFilter('getAllcontentExcludedTaxonomies', getNosearchcategorypostsExcludedTaxonomies(...));
function getNosearchcategorypostsExcludedTaxonomies($excluded_taxonomies)
{
    $excluded_taxonomies['category'] = $excluded_taxonomies['category'] ?? array();
    $excluded_taxonomies['category'] = array_merge(
        $excluded_taxonomies['category'],
        PoP_NoSearchCategoryPosts_Utils::getCats()
    );

    return $excluded_taxonomies;
}

\PoP\Root\App::addFilter('getTheMainCategories', getNosearchcategorypostsTheMainCategories(...));
function getNosearchcategorypostsTheMainCategories($cats)
{
    return array_merge(
        $cats,
        PoP_NoSearchCategoryPosts_Utils::getCats()
    );
}
