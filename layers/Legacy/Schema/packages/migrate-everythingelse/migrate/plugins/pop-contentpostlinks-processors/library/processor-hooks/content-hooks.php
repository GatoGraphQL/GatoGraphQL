<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoPSchema\PostCategories\Facades\PostCategoryTypeAPIFacade;

class PoP_ContentPostLinks_ContentHooks
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
        $vars = ApplicationState::getVars();
        $post_id = $vars['routing']['queried-object-id'];
        $postCategoryTypeAPI = PostCategoryTypeAPIFacade::getInstance();

        if ($module == [PoP_Module_Processor_Contents::class, PoP_Module_Processor_Contents::MODULE_CONTENT_SINGLE] && defined('POP_CONTENTPOSTLINKS_CAT_CONTENTPOSTLINKS') && POP_CONTENTPOSTLINKS_CAT_CONTENTPOSTLINKS && $postCategoryTypeAPI->hasCategory(POP_CONTENTPOSTLINKS_CAT_CONTENTPOSTLINKS, $post_id)) {
            return [PoP_ContentPostLinks_Module_Processor_SingleContentInners::class, PoP_ContentPostLinks_Module_Processor_SingleContentInners::MODULE_CONTENTINNER_LINKSINGLE];
        }

        return $inner;
    }
}

/**
 * Initialization
 */
new PoP_ContentPostLinks_ContentHooks();
