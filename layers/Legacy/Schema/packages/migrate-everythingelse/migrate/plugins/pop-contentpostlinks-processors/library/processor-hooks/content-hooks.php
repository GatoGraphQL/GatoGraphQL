<?php
use PoP\ComponentModel\State\ApplicationState;
use PoPCMSSchema\PostCategories\Facades\PostCategoryTypeAPIFacade;

class PoP_ContentPostLinks_ContentHooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_Contents:inner_component',
            $this->contentInner(...),
            10,
            2
        );
    }

    public function contentInner($inner, array $component)
    {
        $post_id = \PoP\Root\App::getState(['routing', 'queried-object-id']);
        $postCategoryTypeAPI = PostCategoryTypeAPIFacade::getInstance();

        if ($component == [PoP_Module_Processor_Contents::class, PoP_Module_Processor_Contents::MODULE_CONTENT_SINGLE] && defined('POP_CONTENTPOSTLINKS_CAT_CONTENTPOSTLINKS') && POP_CONTENTPOSTLINKS_CAT_CONTENTPOSTLINKS && $postCategoryTypeAPI->hasCategory(POP_CONTENTPOSTLINKS_CAT_CONTENTPOSTLINKS, $post_id)) {
            return [PoP_ContentPostLinks_Module_Processor_SingleContentInners::class, PoP_ContentPostLinks_Module_Processor_SingleContentInners::MODULE_CONTENTINNER_LINKSINGLE];
        }

        return $inner;
    }
}

/**
 * Initialization
 */
new PoP_ContentPostLinks_ContentHooks();
