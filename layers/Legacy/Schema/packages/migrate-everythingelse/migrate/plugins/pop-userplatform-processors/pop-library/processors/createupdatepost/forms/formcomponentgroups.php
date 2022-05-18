<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_AddHighlights_Module_Processor_FormComponentGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const MODULE_FORMCOMPONENTGROUP_CARD_HIGHLIGHTEDPOST = 'formcomponentgroup-card-highlightedpost';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMCOMPONENTGROUP_CARD_HIGHLIGHTEDPOST],
        );
    }

    public function getComponentSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMCOMPONENTGROUP_CARD_HIGHLIGHTEDPOST:
                return [PoP_AddHighlights_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_AddHighlights_Module_Processor_PostTriggerLayoutFormComponentValues::COMPONENT_FORMCOMPONENT_CARD_HIGHLIGHTEDPOST];
        }
        
        return parent::getComponentSubmodule($component);
    }

    public function getLabel(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMCOMPONENTGROUP_CARD_HIGHLIGHTEDPOST:
                return TranslationAPIFacade::getInstance()->__('Highlight from:', 'poptheme-wassup');
        }
        
        return parent::getLabel($component, $props);
    }
}



