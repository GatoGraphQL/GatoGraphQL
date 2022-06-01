<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_SocialNetwork_Module_Processor_FormComponentGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const COMPONENT_FORMCOMPONENTGROUP_CARD_CONTACTUSER = 'formcomponentgroup-card-contactuser';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMCOMPONENTGROUP_CARD_CONTACTUSER,
        );
    }

    public function getComponentSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMCOMPONENTGROUP_CARD_CONTACTUSER:
                return [PoP_Application_Module_Processor_UserTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_UserTriggerLayoutFormComponentValues::COMPONENT_FORMCOMPONENT_CARD_USER];
        }

        return parent::getComponentSubcomponent($component);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        switch ($component->name) {
            case self::COMPONENT_FORMCOMPONENTGROUP_CARD_CONTACTUSER:
                $component = $this->getComponentSubcomponent($component);

                $trigger = $componentprocessor_manager->getProcessor($component)->getTriggerSubcomponent($component);
                $description = sprintf(
                    '<em><label><strong>%s</strong></label></em>',
                    TranslationAPIFacade::getInstance()->__('Send message to:', 'pop-application-processors')
                );
                $this->setProp($trigger, $props, 'description', $description);
                break;
        }

        parent::initModelProps($component, $props);
    }

    public function getLabel(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMCOMPONENTGROUP_CARD_CONTACTUSER:
                return '';
        }

        return parent::getLabel($component, $props);
    }
}



