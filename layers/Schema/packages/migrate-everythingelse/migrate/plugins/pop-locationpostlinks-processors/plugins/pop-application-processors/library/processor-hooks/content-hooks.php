<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoPSchema\Posts\Facades\PostTypeAPIFacade;
use PoP\ComponentModel\State\ApplicationState;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;

class PoPTheme_LocationPostLinks_ContentHooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
            'PoP_Module_Processor_Contents:inner_module',
            array($this, 'contentInner'),
            10,
            2
        );
    }

    public function contentInner($inner, array $module)
    {
        if ($module == [PoP_Module_Processor_Contents::class, PoP_Module_Processor_Contents::MODULE_CONTENT_SINGLE]) {
            $vars = ApplicationState::getVars();
            $postTypeAPI = PostTypeAPIFacade::getInstance();
            $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
            $categoryapi = \PoPSchema\PostCategories\FunctionAPIFactory::getInstance();
            $post_id = $vars['routing-state']['queried-object-id'];
            if (
                $customPostTypeAPI->getCustomPostType($post_id) == $postTypeAPI->getPostCustomPostType()
                && defined('POP_LOCATIONPOSTLINKS_CAT_LOCATIONPOSTLINKS') && POP_LOCATIONPOSTLINKS_CAT_LOCATIONPOSTLINKS
                && $categoryapi->hasCategory(POP_LOCATIONPOSTLINKS_CAT_LOCATIONPOSTLINKS, $post_id)
            ) {
                return [PoP_ContentPostLinks_Module_Processor_SingleContentInners::class, PoP_ContentPostLinks_Module_Processor_SingleContentInners::MODULE_CONTENTINNER_LINKSINGLE];
            }
        }

        return $inner;
    }
}

/**
 * Initialization
 */
new PoPTheme_LocationPostLinks_ContentHooks();
