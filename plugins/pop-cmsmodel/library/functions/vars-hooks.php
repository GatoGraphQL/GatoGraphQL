<?php

\PoP\CMS\HooksAPI_Factory::getInstance()->addFilter('ModelInstanceProcessor:model_instance_components_from_vars', 'popCmsmodelModuleInstanceComponents');
function popCmsmodelModuleInstanceComponents($components)
{
    $vars = \PoP\Engine\Engine_Vars::getVars();
    $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
    $hierarchy = $vars['hierarchy'];

    // Properties specific to each hierarchy
    switch ($hierarchy) {
        case GD_SETTINGS_HIERARCHY_AUTHOR:
            $author = $vars['global-state']['queried-object-id'];

            // Author: it may depend on its role
            $component_types = \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters(
                '\PoP\Engine\ModelInstanceProcessor_Utils:components_from_vars:type:author',
                array(
                    POP_MODELINSTANCECOMPONENTTYPE_AUTHOR_ROLE,
                )
            );
            if (in_array(POP_MODELINSTANCECOMPONENTTYPE_AUTHOR_ROLE, $component_types)) {
                $components[] = __('author role:', 'pop-engine').getTheUserRole($author);
            }
            break;

        case GD_SETTINGS_HIERARCHY_SINGLE:
            $post_id = $vars['global-state']['queried-object-id'];

            // Single may depend on its post_type and category
            // Post and Event may be different
            // Announcements and Articles (Posts), or Past Event and (Upcoming) Event may be different
            // By default, we check for post type but not for categories
            $component_types = \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters(
                '\PoP\Engine\ModelInstanceProcessor_Utils:components_from_vars:type:single',
                array(
                    POP_MODELINSTANCECOMPONENTTYPE_SINGLE_POSTTYPE,
                )
            );
            if (in_array(POP_MODELINSTANCECOMPONENTTYPE_SINGLE_POSTTYPE, $component_types)) {
                $components[] = __('post type:', 'pop-engine').$cmsapi->getPostType($post_id);
            }
            if (in_array(POP_MODELINSTANCECOMPONENTTYPE_SINGLE_CATEGORIES, $component_types)) {
                if ($categories = $this->getSingleCategories($post_id)) {
                    $components[] = __('categories:', 'pop-engine').implode('.', $categories);
                }
            }
            break;
    }

    return $components;
}
