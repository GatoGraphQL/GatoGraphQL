<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
// Remove all categories from this plugin, we will add the corresponding resourceloader mapping in hooks.php
add_filter('PoPThemeWassup_ResourceLoader_Hooks:single_resources:categories', 'poptheme_wassup_categories_remove_cats_from_singleresources');
function poptheme_wassup_categories_remove_cats_from_singleresources($categories) {

    // For POP_CATEGORYPROCESSORS_CONFIGUTILS_WEBPOSTS no need to generate the configuration, because all these posts
    // already have category POPTHEME_WASSUP_CAT_WEBPOSTS, so they will be generated in poptheme-wassup already
    return array_diff(
        $categories,
        PoPTheme_Wassup_CategoryProcessors_ConfigUtils::get_cats(array(POP_CATEGORYPROCESSORS_CONFIGUTILS_WEBPOSTS))
    );
}