<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoPSchema\PostCategories\Facades\PostCategoryTypeAPIFacade;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;

class PoP_PostCategoryLayouts_LayoutDataloadHooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
            'PoP_Application:TypeResolver_Posts:multilayout-keys',
            array($this, 'addMultilayoutKeys'),
            10,
            3
        );
    }

    public function addMultilayoutKeys($keys, $post_id, $relationalTypeResolver)
    {
        $postCategoryTypeAPI = PostCategoryTypeAPIFacade::getInstance();
        if (in_array(POP_POSTCATEGORYLAYOUTS_CATEGORIES_LAYOUTFEATUREIMAGE, $postCategoryTypeAPI->getCustomPostCategories($post_id, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]))) {
            // Priority: place it before the 'postType' layout key
            array_unshift($keys, strtolower($relationalTypeResolver->getTypeName()).'-featureimage');
        }

        return $keys;
    }
}

/**
 * Initialize
 */
new PoP_PostCategoryLayouts_LayoutDataloadHooks();
