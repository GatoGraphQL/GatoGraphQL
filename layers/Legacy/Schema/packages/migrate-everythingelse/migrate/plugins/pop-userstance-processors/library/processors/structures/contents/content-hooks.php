<?php
use PoPCMSSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;

class PoPTheme_UserStance_ContentHooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_Contents:inner_component',
            $this->getContentInnerComponent(...),
            10,
            2
        );
    }

    public function getContentInnerComponent(\PoP\ComponentModel\Component\Component $inner, \PoP\ComponentModel\Component\Component $component): \PoP\ComponentModel\Component\Component
    {
        if ($component == [PoP_Module_Processor_Contents::class, PoP_Module_Processor_Contents::COMPONENT_CONTENT_SINGLE] || $component == [PoP_Module_Processor_Contents::class, PoP_Module_Processor_Contents::COMPONENT_CONTENT_USERPOSTINTERACTION]) {
            $post_id = \PoP\Root\App::getState(['routing', 'queried-object-id']);
            $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
            if ($customPostTypeAPI->getCustomPostType($post_id) == POP_USERSTANCE_POSTTYPE_USERSTANCE) {
                if (($component == [PoP_Module_Processor_Contents::class, PoP_Module_Processor_Contents::COMPONENT_CONTENT_SINGLE])) {
                    return [UserStance_Module_Processor_SingleContentInners::class, UserStance_Module_Processor_SingleContentInners::COMPONENT_CONTENTINNER_STANCESINGLE];
                }
                if (($component == [PoP_Module_Processor_Contents::class, PoP_Module_Processor_Contents::COMPONENT_CONTENT_USERPOSTINTERACTION])) {
                    return [UserStance_Module_Processor_SingleContentInners::class, UserStance_Module_Processor_SingleContentInners::COMPONENT_CONTENTINNER_USERSTANCEPOSTINTERACTION];
                }
            }
        }

        return $inner;
    }
}

/**
 * Initialization
 */
new PoPTheme_UserStance_ContentHooks();
