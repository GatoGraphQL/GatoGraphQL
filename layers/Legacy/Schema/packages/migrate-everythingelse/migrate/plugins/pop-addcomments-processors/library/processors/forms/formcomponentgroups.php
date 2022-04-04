<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_AddComment_Module_Processor_FormInputGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const MODULE_FORMCOMPONENTGROUP_CARD_COMMENTPOST = 'formcomponentgroup-card-commentpost';
    public final const MODULE_FORMCOMPONENTGROUP_CARD_PARENTCOMMENT = 'formcomponentgroup-card-parentcomment';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMCOMPONENTGROUP_CARD_COMMENTPOST],
            [self::class, self::MODULE_FORMCOMPONENTGROUP_CARD_PARENTCOMMENT],
        );
    }

    public function getComponentSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FORMCOMPONENTGROUP_CARD_COMMENTPOST:
                return [PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::MODULE_FORMCOMPONENT_CARD_COMMENTPOST];

            case self::MODULE_FORMCOMPONENTGROUP_CARD_PARENTCOMMENT:
                return [PoP_Application_Module_Processor_CommentTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_CommentTriggerLayoutFormComponentValues::MODULE_FORMCOMPONENT_CARD_COMMENT];
        }

        return parent::getComponentSubmodule($module);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_FORMCOMPONENTGROUP_CARD_COMMENTPOST:
            case self::MODULE_FORMCOMPONENTGROUP_CARD_PARENTCOMMENT:
                $component = $this->getComponentSubmodule($module);

                $alert_classes = array(
                    self::MODULE_FORMCOMPONENTGROUP_CARD_COMMENTPOST => 'alert-sm alert-warning',
                    self::MODULE_FORMCOMPONENTGROUP_CARD_PARENTCOMMENT => 'bg-warning',
                );

                $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
                $trigger = $moduleprocessor_manager->getProcessor($component)->getTriggerSubmodule($component);

                $descriptions = array(
                    self::MODULE_FORMCOMPONENTGROUP_CARD_COMMENTPOST => TranslationAPIFacade::getInstance()->__('Add a comment for:', 'pop-application-processors'),
                    self::MODULE_FORMCOMPONENTGROUP_CARD_PARENTCOMMENT => TranslationAPIFacade::getInstance()->__('In response to:', 'pop-application-processors'),
                );
                $description = sprintf(
                    '<em><label><strong>%s</strong></label></em>',
                    $descriptions[$module[1]]
                );
                $this->setProp($trigger, $props, 'description', $description);
                break;
        }

        parent::initModelProps($module, $props);
    }

    public function getLabel(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMCOMPONENTGROUP_CARD_COMMENTPOST:
            case self::MODULE_FORMCOMPONENTGROUP_CARD_PARENTCOMMENT:
                return '';
        }

        return parent::getLabel($module, $props);
    }
}



