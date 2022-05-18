<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Volunteering_Module_Processor_FormComponentGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const MODULE_FORMCOMPONENTGROUP_CARD_VOLUNTEER = 'formcomponentgroup-card-volunteer';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMCOMPONENTGROUP_CARD_VOLUNTEER],
        );
    }

    public function getComponentSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMCOMPONENTGROUP_CARD_VOLUNTEER:
                return [PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::COMPONENT_FORMCOMPONENT_CARD_POST];
        }

        return parent::getComponentSubmodule($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        switch ($component[1]) {
            case self::COMPONENT_FORMCOMPONENTGROUP_CARD_VOLUNTEER:
                $component = $this->getComponentSubmodule($component);

                $trigger = $componentprocessor_manager->getProcessor($component)->getTriggerSubmodule($component);
                $description = sprintf(
                    '<em><label><strong>%s</strong></label></em>',
                    TranslationAPIFacade::getInstance()->__('Volunteer for:', 'pop-application-processors')
                );
                $this->setProp($trigger, $props, 'description', $description);
                break;
        }

        parent::initModelProps($component, $props);
    }

    public function getLabel(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMCOMPONENTGROUP_CARD_VOLUNTEER:
                return '';
        }

        return parent::getLabel($component, $props);
    }
}



