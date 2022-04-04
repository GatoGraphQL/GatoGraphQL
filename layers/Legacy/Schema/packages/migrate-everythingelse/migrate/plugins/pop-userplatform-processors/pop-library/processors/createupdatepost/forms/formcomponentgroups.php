<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_AddHighlights_Module_Processor_FormComponentGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const MODULE_FORMCOMPONENTGROUP_CARD_HIGHLIGHTEDPOST = 'formcomponentgroup-card-highlightedpost';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMCOMPONENTGROUP_CARD_HIGHLIGHTEDPOST],
        );
    }

    public function getComponentSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FORMCOMPONENTGROUP_CARD_HIGHLIGHTEDPOST:
                return [PoP_AddHighlights_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_AddHighlights_Module_Processor_PostTriggerLayoutFormComponentValues::MODULE_FORMCOMPONENT_CARD_HIGHLIGHTEDPOST];
        }
        
        return parent::getComponentSubmodule($module);
    }

    public function getLabel(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMCOMPONENTGROUP_CARD_HIGHLIGHTEDPOST:
                return TranslationAPIFacade::getInstance()->__('Highlight from:', 'poptheme-wassup');
        }
        
        return parent::getLabel($module, $props);
    }
}



