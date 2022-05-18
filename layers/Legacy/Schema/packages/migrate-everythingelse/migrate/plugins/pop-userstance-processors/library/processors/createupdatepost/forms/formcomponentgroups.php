<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class UserStance_Module_Processor_FormComponentGroupsGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const MODULE_FORMCOMPONENTGROUP_CARD_STANCETARGET = 'formcomponentgroup-card-stancetarget';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMCOMPONENTGROUP_CARD_STANCETARGET],
        );
    }

    public function getComponentSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMCOMPONENTGROUP_CARD_STANCETARGET:
                return [PoP_UserStance_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_UserStance_Module_Processor_PostTriggerLayoutFormComponentValues::COMPONENT_FORMCOMPONENT_CARD_STANCETARGET];
        }

        return parent::getComponentSubmodule($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        switch ($component[1]) {
            case self::COMPONENT_FORMCOMPONENTGROUP_CARD_STANCETARGET:
                $this->appendProp($component, $props, 'class', 'pop-uniqueornone-selectabletypeahead-formgroup');

                $component = $this->getComponentSubmodule($component);

                $trigger = $componentprocessor_manager->getProcessor($component)->getTriggerSubmodule($component);
                $description = sprintf(
                    '<em><label><strong>%s</strong></label></em>',
                    TranslationAPIFacade::getInstance()->__('After reading...', 'pop-userstance-processors')
                );
                $this->setProp($trigger, $props, 'description', $description);
                break;
        }

        parent::initModelProps($component, $props);
    }

    public function getLabel(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMCOMPONENTGROUP_CARD_STANCETARGET:
                return '';
        }

        return parent::getLabel($component, $props);
    }
}



