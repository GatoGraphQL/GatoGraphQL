<?php

use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\CustomPosts\Routing\RouteNatures as CustomPostRouteNatures;

HooksAPIFacade::getInstance()->addFilter(\PoP\ComponentModel\ModelInstance\ModelInstance::HOOK_COMPONENTS_RESULT, 'popTaxonomyModuleInstanceComponents');
function popTaxonomyModuleInstanceComponents($components)
{
    $vars = ApplicationState::getVars();
    $categoryapi = \PoPSchema\PostCategories\FunctionAPIFactory::getInstance();
    $nature = $vars['nature'];

    // Properties specific to each nature
    switch ($nature) {
        case CustomPostRouteNatures::CUSTOMPOST:
            // Single may depend on its post_type and category
            // Post and Event may be different
            // Announcements and Articles (Posts), or Past Event and (Upcoming) Event may be different
            // By default, we check for post type but not for categories
            $component_types = HooksAPIFacade::getInstance()->applyFilters(
                '\PoPSchema\Categories\ModelInstanceProcessor_Utils:components_from_vars:type:single',
                []
            );
            if (in_array(POP_MODELINSTANCECOMPONENTTYPE_SINGLE_CATEGORIES, $component_types)) {
                $post_id = $vars['routing-state']['queried-object-id'];
                $categories = array();
                $cmstaxonomiesresolver = \PoPSchema\Categories\ObjectPropertyResolverFactory::getInstance();
                foreach ($categoryapi->getCustomPostCategories($post_id) as $cat) {
                    $categories[] = $cmstaxonomiesresolver->getCategorySlug($cat) . $cmstaxonomiesresolver->getCategoryID($cat);
                }

                // Allow for plug-ins to add their own categories. Eg: Events
                $categories = HooksAPIFacade::getInstance()->applyFilters('ModelInstanceProcessor:getCategories', $categories, $post_id);
                if ($categories) {
                    $components[] = TranslationAPIFacade::getInstance()->__('categories:', 'pop-engine') . implode('.', $categories);
                }
            }
            break;
    }

    return $components;
}
