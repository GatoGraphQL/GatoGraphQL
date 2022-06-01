<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_CreateUpdatePostFormGroups extends PoP_Module_Processor_FormGroupsBase
{
    public final const COMPONENT_FORMGROUP_EMBEDPREVIEW = 'formgroup-embedpreview';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMGROUP_EMBEDPREVIEW],
        );
    }

    public function getComponentSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMGROUP_EMBEDPREVIEW:
                return [PoP_Module_Processor_EmbedPreviewLayouts::class, PoP_Module_Processor_EmbedPreviewLayouts::COMPONENT_LAYOUT_USERINPUTEMBEDPREVIEW];
        }
        
        return parent::getComponentSubcomponent($component);
    }

    public function getLabel(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMGROUP_EMBEDPREVIEW:
                return TranslationAPIFacade::getInstance()->__('Preview', 'poptheme-wassup');
        }
        
        return parent::getLabel($component, $props);
    }
}



