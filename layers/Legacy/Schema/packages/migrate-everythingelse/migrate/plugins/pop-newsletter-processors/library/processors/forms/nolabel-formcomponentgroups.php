<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Newsletter_Module_Processor_NoLabelFormComponentGroups extends PoP_Module_Processor_NoLabelFormComponentGroupsBase
{
    public final const COMPONENT_FORMINPUTGROUP_CUP_NEWSLETTER = 'forminputgroup-cup-newsletter';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMINPUTGROUP_CUP_NEWSLETTER,
        );
    }

    public function getInfo(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUTGROUP_CUP_NEWSLETTER:
                return TranslationAPIFacade::getInstance()->__('Keep up to date with our community activity through our weekly newsletter.', 'pop-genericforms');
        }

        return parent::getInfo($component, $props);
    }

    public function getComponentSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $components = array(
            self::COMPONENT_FORMINPUTGROUP_CUP_NEWSLETTER => [GenericForms_Module_Processor_CheckboxFormInputs::class, GenericForms_Module_Processor_CheckboxFormInputs::COMPONENT_FORMINPUT_CUP_NEWSLETTER],
        );

        if ($component = $components[$component->name] ?? null) {
            return $component;
        }

        return parent::getComponentSubcomponent($component);
    }
}



