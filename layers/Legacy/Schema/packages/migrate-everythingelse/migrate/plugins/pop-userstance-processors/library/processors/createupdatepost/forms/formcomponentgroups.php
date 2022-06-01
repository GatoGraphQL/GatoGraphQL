<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class UserStance_Module_Processor_FormComponentGroupsGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const COMPONENT_FORMCOMPONENTGROUP_CARD_STANCETARGET = 'formcomponentgroup-card-stancetarget';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMCOMPONENTGROUP_CARD_STANCETARGET],
        );
    }

    public function getComponentSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMCOMPONENTGROUP_CARD_STANCETARGET:
                return [PoP_UserStance_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_UserStance_Module_Processor_PostTriggerLayoutFormComponentValues::COMPONENT_FORMCOMPONENT_CARD_STANCETARGET];
        }

        return parent::getComponentSubcomponent($component);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        switch ($component[1]) {
            case self::COMPONENT_FORMCOMPONENTGROUP_CARD_STANCETARGET:
                $this->appendProp($component, $props, 'class', 'pop-uniqueornone-selectabletypeahead-formgroup');

                $component = $this->getComponentSubcomponent($component);

                $trigger = $componentprocessor_manager->getProcessor($component)->getTriggerSubcomponent($component);
                $description = sprintf(
                    '<em><label><strong>%s</strong></label></em>',
                    TranslationAPIFacade::getInstance()->__('After reading...', 'pop-userstance-processors')
                );
                $this->setProp($trigger, $props, 'description', $description);
                break;
        }

        parent::initModelProps($component, $props);
    }

    public function getLabel(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMCOMPONENTGROUP_CARD_STANCETARGET:
                return '';
        }

        return parent::getLabel($component, $props);
    }
}



