<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_AddComment_Module_Processor_FormInputGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const MODULE_FORMCOMPONENTGROUP_CARD_COMMENTPOST = 'formcomponentgroup-card-commentpost';
    public final const MODULE_FORMCOMPONENTGROUP_CARD_PARENTCOMMENT = 'formcomponentgroup-card-parentcomment';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMCOMPONENTGROUP_CARD_COMMENTPOST],
            [self::class, self::MODULE_FORMCOMPONENTGROUP_CARD_PARENTCOMMENT],
        );
    }

    public function getComponentSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_FORMCOMPONENTGROUP_CARD_COMMENTPOST:
                return [PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::MODULE_FORMCOMPONENT_CARD_COMMENTPOST];

            case self::MODULE_FORMCOMPONENTGROUP_CARD_PARENTCOMMENT:
                return [PoP_Application_Module_Processor_CommentTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_CommentTriggerLayoutFormComponentValues::MODULE_FORMCOMPONENT_CARD_COMMENT];
        }

        return parent::getComponentSubmodule($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::MODULE_FORMCOMPONENTGROUP_CARD_COMMENTPOST:
            case self::MODULE_FORMCOMPONENTGROUP_CARD_PARENTCOMMENT:
                $component = $this->getComponentSubmodule($component);

                $alert_classes = array(
                    self::MODULE_FORMCOMPONENTGROUP_CARD_COMMENTPOST => 'alert-sm alert-warning',
                    self::MODULE_FORMCOMPONENTGROUP_CARD_PARENTCOMMENT => 'bg-warning',
                );

                $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
                $trigger = $componentprocessor_manager->getProcessor($component)->getTriggerSubmodule($component);

                $descriptions = array(
                    self::MODULE_FORMCOMPONENTGROUP_CARD_COMMENTPOST => TranslationAPIFacade::getInstance()->__('Add a comment for:', 'pop-application-processors'),
                    self::MODULE_FORMCOMPONENTGROUP_CARD_PARENTCOMMENT => TranslationAPIFacade::getInstance()->__('In response to:', 'pop-application-processors'),
                );
                $description = sprintf(
                    '<em><label><strong>%s</strong></label></em>',
                    $descriptions[$component[1]]
                );
                $this->setProp($trigger, $props, 'description', $description);
                break;
        }

        parent::initModelProps($component, $props);
    }

    public function getLabel(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_FORMCOMPONENTGROUP_CARD_COMMENTPOST:
            case self::MODULE_FORMCOMPONENTGROUP_CARD_PARENTCOMMENT:
                return '';
        }

        return parent::getLabel($component, $props);
    }
}



