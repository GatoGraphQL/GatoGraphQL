<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Newsletter_Module_Processor_NoLabelFormComponentGroups extends PoP_Module_Processor_NoLabelFormComponentGroupsBase
{
    public final const MODULE_FORMINPUTGROUP_CUP_NEWSLETTER = 'forminputgroup-cup-newsletter';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUTGROUP_CUP_NEWSLETTER],
        );
    }

    public function getInfo(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_FORMINPUTGROUP_CUP_NEWSLETTER:
                return TranslationAPIFacade::getInstance()->__('Keep up to date with our community activity through our weekly newsletter.', 'pop-genericforms');
        }

        return parent::getInfo($component, $props);
    }

    public function getComponentSubmodule(array $component)
    {
        $components = array(
            self::MODULE_FORMINPUTGROUP_CUP_NEWSLETTER => [GenericForms_Module_Processor_CheckboxFormInputs::class, GenericForms_Module_Processor_CheckboxFormInputs::MODULE_FORMINPUT_CUP_NEWSLETTER],
        );

        if ($component = $components[$component[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubmodule($component);
    }
}



