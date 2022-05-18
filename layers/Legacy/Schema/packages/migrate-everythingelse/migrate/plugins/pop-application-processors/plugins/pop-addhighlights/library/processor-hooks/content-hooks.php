<?php
use PoP\ComponentModel\State\ApplicationState;
use PoPCMSSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;

class PoPTheme_AddHighlights_Processors_ContentHooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_Contents:inner_componentVariation',
            $this->contentInner(...),
            10,
            2
        );
    }

    public function contentInner($inner, array $componentVariation)
    {
        if ($componentVariation == [PoP_Module_Processor_Contents::class, PoP_Module_Processor_Contents::MODULE_CONTENT_SINGLE] || $componentVariation == [PoP_Module_Processor_Contents::class, PoP_Module_Processor_Contents::MODULE_CONTENT_USERPOSTINTERACTION]) {
            $post_id = \PoP\Root\App::getState(['routing', 'queried-object-id']);
            $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
            if ($customPostTypeAPI->getCustomPostType($post_id) == POP_ADDHIGHLIGHTS_POSTTYPE_HIGHLIGHT) {
                if (($componentVariation == [PoP_Module_Processor_Contents::class, PoP_Module_Processor_Contents::MODULE_CONTENT_SINGLE])) {
                    return [PoP_Module_Processor_SingleContentInners::class, PoP_Module_Processor_SingleContentInners::MODULE_CONTENTINNER_HIGHLIGHTSINGLE];
                } elseif (($componentVariation == [PoP_Module_Processor_Contents::class, PoP_Module_Processor_Contents::MODULE_CONTENT_USERPOSTINTERACTION])) {
                    return [PoP_Module_Processor_SingleContentInners::class, PoP_Module_Processor_SingleContentInners::MODULE_CONTENTINNER_USERHIGHLIGHTPOSTINTERACTION];
                }
            }
        }

        return $inner;
    }
}

/**
 * Initialization
 */
new PoPTheme_AddHighlights_Processors_ContentHooks();
