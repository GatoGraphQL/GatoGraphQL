<?php
use PoPSchema\PostCategories\Facades\PostCategoryTypeAPIFacade;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\Taxonomies\Facades\TaxonomyTypeAPIFacade;

function getAllcontentExcludedTaxonomies()
{
    return \PoP\Root\App::getHookManager()->applyFilters('getAllcontentExcludedTaxonomies', array());
}

function useAllcontentCategories()
{
    return \PoP\Root\App::getHookManager()->applyFilters('useAllcontentCategories', true);
}

function gdDataloadAllcontentCategories()
{
    if (useAllcontentCategories()) {

        $postCategoryTypeAPI = PostCategoryTypeAPIFacade::getInstance();
        $all_categories = $postCategoryTypeAPI->getCategories([], [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);

        // All Post Categories but the excluded ones
        $excluded_taxonomies = getAllcontentExcludedTaxonomies();
        if ($excluded_categories = $excluded_taxonomies['category']) {
            return array_diff(
                $all_categories,
                $excluded_categories
            );
        }

        // Return all categories
        return $all_categories;
    }

    // If there are no excluded categories, then return an empty array, meaning that all categories are valid
    return array();
}

function gdDataloadAllcontentComponents()
{

    // Calculate all the terms automatically, by querying the category-like taxonomies from all searchable post types,
    // and getting all the terms (categories) within
    $cmsapplicationpostsapi = \PoP\Application\PostsFunctionAPIFactory::getInstance();
    $taxonomyapi = TaxonomyTypeAPIFacade::getInstance();
    $excluded_terms = getAllcontentExcludedTaxonomies();
    $components = array();
    foreach ($cmsapplicationpostsapi->getAllcontentPostTypes() as $post_type) {
        $component = array(
            'postType' => $post_type,
        );
        if ($taxonomies = $taxonomyapi->getCustomPostTypeTaxonomies($post_type)) {
            foreach ($taxonomies as $taxonomy) {
                // Only for Category-type taxonomies, not for term-like
                if ($taxonomyapi->isTaxonomyHierarchical($taxonomy)) {
                    $component['taxonomy'] = $taxonomy;
                    if ($terms = $taxonomyapi->getTaxonomyTerms($taxonomy, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS])) {
                        if ($terms = array_diff(
                            $terms,
                            $excluded_terms[$taxonomy] ?? array()
                        )) {
                            $component['terms'] = $terms;
                        }
                    }
                }
            }
        }

        $components[] = $component;
    }

    return $components;
}

