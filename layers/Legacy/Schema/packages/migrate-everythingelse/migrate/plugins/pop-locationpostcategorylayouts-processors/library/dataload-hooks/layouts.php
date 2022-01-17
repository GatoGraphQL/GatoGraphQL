<?php
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPCMSSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPCMSSchema\LocationPosts\TypeResolvers\ObjectType\LocationPostObjectTypeResolver;
use PoPCMSSchema\Taxonomies\Facades\TaxonomyTypeAPIFacade;

class PoP_LocationPostCategoryLayouts_LayoutDataloadHooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_Application:TypeResolver_Posts:multilayout-keys',
            array($this, 'addMultilayoutKeys'),
            10,
            2
        );
    }

    public function addMultilayoutKeys($keys, $post_id)
    {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        $taxonomyapi = TaxonomyTypeAPIFacade::getInstance();
        if ($customPostTypeAPI->getCustomPostType($post_id) == POP_LOCATIONPOSTS_POSTTYPE_LOCATIONPOST) {
            // Add the layout if the post has any of the defined categories for the Map Layout
            $add_layout = false;
            foreach (POP_LOCATIONPOSTCATEGORYLAYOUTS_CATEGORIES_LAYOUTMAP as $cat) {
                if ($add_layout = $taxonomyapi->hasTerm($cat, POP_LOCATIONPOSTS_TAXONOMY_CATEGORY, $post_id)) {
                    break;
                }
            }
            if ($add_layout) {
                // Priority: place it before the 'postType' layout key
                $instanceManager = InstanceManagerFacade::getInstance();
                /** @var RelationalTypeResolverInterface */
                $locationPostTypeResolver = $instanceManager->getInstance(LocationPostObjectTypeResolver::class);
                array_unshift($keys, $locationPostTypeResolver->getTypeName() . '-map');
            }
        }
        return $keys;
    }
}

/**
 * Initialize
 */
new PoP_LocationPostCategoryLayouts_LayoutDataloadHooks();
