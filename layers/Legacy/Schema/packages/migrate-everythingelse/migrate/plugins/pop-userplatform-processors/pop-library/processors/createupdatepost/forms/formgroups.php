<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_CreateUpdatePostFormGroups extends PoP_Module_Processor_FormGroupsBase
{
    public final const MODULE_FORMGROUP_EMBEDPREVIEW = 'formgroup-embedpreview';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMGROUP_EMBEDPREVIEW],
        );
    }

    public function getComponentSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FORMGROUP_EMBEDPREVIEW:
                return [PoP_Module_Processor_EmbedPreviewLayouts::class, PoP_Module_Processor_EmbedPreviewLayouts::MODULE_LAYOUT_USERINPUTEMBEDPREVIEW];
        }
        
        return parent::getComponentSubmodule($module);
    }

    public function getLabel(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMGROUP_EMBEDPREVIEW:
                return TranslationAPIFacade::getInstance()->__('Preview', 'poptheme-wassup');
        }
        
        return parent::getLabel($module, $props);
    }
}



