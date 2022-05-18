<?php

class PoP_Mentions_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_EditorFormInputsBase:editor_layouts',
            $this->addLayoutSubmodules(...)
        );
    }

    public function addLayoutSubmodules($layouts)
    {

        // Add the required layouts to the editor, so it's loaded and available when needed
        $layouts[] = [PoP_Module_Processor_UserMentionComponentLayouts::class, PoP_Module_Processor_UserMentionComponentLayouts::COMPONENT_LAYOUTUSER_MENTION_COMPONENT];
        $layouts[] = [PoP_Module_Processor_TagMentionComponentLayouts::class, PoP_Module_Processor_TagMentionComponentLayouts::COMPONENT_LAYOUTTAG_MENTION_COMPONENT];
        return $layouts;
    }
}

/**
 * Initialization
 */
new PoP_Mentions_Hooks();
