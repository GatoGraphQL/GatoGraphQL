<?php

class PoPTheme_Wassup_PageSectionHooks
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addAction(
            'PoP_Module_Processor_CustomTabPanePageSections:get_props_block_initial:sideinfo',
            array($this, 'initModelPropsSideinfo'),
            10,
            3
        );
    }

    public function initModelPropsSideinfo(array $module, $props_in_array, $processor)
    {
        $props = &$props_in_array[0];

        $submodule = [PoP_TrendingTags_Module_Processor_SectionBlocks::class, PoP_TrendingTags_Module_Processor_SectionBlocks::MODULE_BLOCK_TRENDINGTAGS_SCROLL_LIST];
        // if (in_array($submodule, $processor->getSubmodules($module))) {

        // We need to lazy-load it, so that it doesn't change the ETag value
        // for when visiting any one page on the site (eg: viewing a post should not say "click here to update" since the post itself was not updated,
        // only the sideinfo with some unrelated content was)
        if (defined('POP_SERVICEWORKERS_INITIALIZED')) {
            if (!PoP_ServiceWorkers_ServerUtils::disableServiceworkers()) {
                $processor->setProp($submodule, $props, 'lazy-load', true);
            }
        }

        // Comment Leo 29/10/2017: we can't use skeleton screen, since it will then load posts
        // which may change the ETag value for the page... not worth it
        // // Use the Skeleton screen to load the lazy-load content
        // $processor->setProp($submodule, $ret, 'use-skeletonscreen', true);
        // }

        // Formatting
        $processor->setProp([PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_TAGS_LIST], $props, 'show-fetchmore', false);
        $processor->setProp($submodule, $props, 'title-htmltag', 'h4');
        $processor->setProp($submodule, $props, 'add-titlelink', true);

        // Limit to only few elems
        $processor->setProp([PoP_TrendingTags_Module_Processor_SectionDataloads::class, PoP_TrendingTags_Module_Processor_SectionDataloads::MODULE_DATALOAD_TRENDINGTAGS_SCROLL_LIST], $props, 'limit', 5);
        // }
    }
}

/**
 * Initialization
 */
new PoPTheme_Wassup_PageSectionHooks();
