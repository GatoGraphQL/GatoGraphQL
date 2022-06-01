<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_AddComment_Module_Processor_FormInputGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const COMPONENT_FORMCOMPONENTGROUP_CARD_COMMENTPOST = 'formcomponentgroup-card-commentpost';
    public final const COMPONENT_FORMCOMPONENTGROUP_CARD_PARENTCOMMENT = 'formcomponentgroup-card-parentcomment';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMCOMPONENTGROUP_CARD_COMMENTPOST,
            self::COMPONENT_FORMCOMPONENTGROUP_CARD_PARENTCOMMENT,
        );
    }

    public function getComponentSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMCOMPONENTGROUP_CARD_COMMENTPOST:
                return [PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::COMPONENT_FORMCOMPONENT_CARD_COMMENTPOST];

            case self::COMPONENT_FORMCOMPONENTGROUP_CARD_PARENTCOMMENT:
                return [PoP_Application_Module_Processor_CommentTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_CommentTriggerLayoutFormComponentValues::COMPONENT_FORMCOMPONENT_CARD_COMMENT];
        }

        return parent::getComponentSubcomponent($component);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_FORMCOMPONENTGROUP_CARD_COMMENTPOST:
            case self::COMPONENT_FORMCOMPONENTGROUP_CARD_PARENTCOMMENT:
                $component = $this->getComponentSubcomponent($component);

                $alert_classes = array(
                    self::COMPONENT_FORMCOMPONENTGROUP_CARD_COMMENTPOST => 'alert-sm alert-warning',
                    self::COMPONENT_FORMCOMPONENTGROUP_CARD_PARENTCOMMENT => 'bg-warning',
                );

                $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
                $trigger = $componentprocessor_manager->getProcessor($component)->getTriggerSubcomponent($component);

                $descriptions = array(
                    self::COMPONENT_FORMCOMPONENTGROUP_CARD_COMMENTPOST => TranslationAPIFacade::getInstance()->__('Add a comment for:', 'pop-application-processors'),
                    self::COMPONENT_FORMCOMPONENTGROUP_CARD_PARENTCOMMENT => TranslationAPIFacade::getInstance()->__('In response to:', 'pop-application-processors'),
                );
                $description = sprintf(
                    '<em><label><strong>%s</strong></label></em>',
                    $descriptions[$component->name]
                );
                $this->setProp($trigger, $props, 'description', $description);
                break;
        }

        parent::initModelProps($component, $props);
    }

    public function getLabel(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMCOMPONENTGROUP_CARD_COMMENTPOST:
            case self::COMPONENT_FORMCOMPONENTGROUP_CARD_PARENTCOMMENT:
                return '';
        }

        return parent::getLabel($component, $props);
    }
}



