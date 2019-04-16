<?php

\PoP\CMS\HooksAPI_Factory::getInstance()->addFilter('ModelInstanceProcessor:model_instance_components_from_vars', 'popTaxonomyModuleInstanceComponents');
function popTaxonomyModuleInstanceComponents($components)
{
    $vars = \PoP\Engine\Engine_Vars::getVars();
    $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
    $taxonomyapi = \PoP\Taxonomy\FunctionAPI_Factory::getInstance();
    $nature = $vars['nature'];

    // Properties specific to each nature
    switch ($nature) {
        
        case POP_NATURE_SINGLE:
            
            // Single may depend on its post_type and category
            // Post and Event may be different
            // Announcements and Articles (Posts), or Past Event and (Upcoming) Event may be different
            // By default, we check for post type but not for categories
            $component_types = \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters(
                '\PoP\Taxonomy\ModelInstanceProcessor_Utils:components_from_vars:type:single',
                []
            );
            if (in_array(POP_MODELINSTANCECOMPONENTTYPE_SINGLE_CATEGORIES, $component_types)) {
                $post_id = $vars['routing-state']['queried-object-id'];
                $categories = array();
                if ($cmsapi->getPostType($post_id) == 'post') {
                    foreach ($taxonomyapi->getPostCategories($post_id) as $cat) {
                        $categories[] = $cat->slug.$cat->term_id;
                    }
                }

                // Allow for plug-ins to add their own categories. Eg: Events
                $categories = \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters('ModelInstanceProcessor:getCategories', $categories, $post_id);
                if ($categories) {
                    $components[] = __('categories:', 'pop-engine').implode('.', $categories);
                }
            }
            break;
    }

    return $components;
}
