<?php

class PoP_PostCategoryLayouts_CategoryPosts_LayoutHooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_CategoryPosts_Module_Processor_ScrollInners:layout',
            array($this, 'getCategoryLayout'),
            10,
            2
        );
    }

    public function getCategoryLayout($layout, $category)
    {
        if (in_array($category, POP_POSTCATEGORYLAYOUTS_CATEGORIES_LAYOUTFEATUREIMAGE)) {
            return [PoP_PostCategoryLayouts_Module_Processor_SimpleViewPreviewPostLayouts::class, PoP_PostCategoryLayouts_Module_Processor_SimpleViewPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_SIMPLEVIEW_FEATUREIMAGE];
        }

        return $layout;
    }
}

/**
 * Initialization
 */
new PoP_PostCategoryLayouts_CategoryPosts_LayoutHooks();
