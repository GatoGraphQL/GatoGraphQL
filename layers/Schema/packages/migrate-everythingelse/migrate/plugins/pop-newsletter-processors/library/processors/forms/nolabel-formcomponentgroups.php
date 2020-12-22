<?php
use PoP\Translation\Facades\TranslationAPIFacade;

class PoP_Newsletter_Module_Processor_NoLabelFormComponentGroups extends PoP_Module_Processor_NoLabelFormComponentGroupsBase
{
    public const MODULE_FORMINPUTGROUP_CUP_NEWSLETTER = 'forminputgroup-cup-newsletter';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUTGROUP_CUP_NEWSLETTER],
        );
    }

    public function getInfo(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUTGROUP_CUP_NEWSLETTER:
                return TranslationAPIFacade::getInstance()->__('Keep up to date with our community activity through our weekly newsletter.', 'pop-genericforms');
        }
        
        return parent::getInfo($module, $props);
    }

    public function getComponentSubmodule(array $module)
    {
        $components = array(
            self::MODULE_FORMINPUTGROUP_CUP_NEWSLETTER => [GenericForms_Module_Processor_CheckboxFormInputs::class, GenericForms_Module_Processor_CheckboxFormInputs::MODULE_FORMINPUT_CUP_NEWSLETTER],
        );

        if ($component = $components[$module[1]]) {
            return $component;
        }
        
        return parent::getComponentSubmodule($module);
    }
}



