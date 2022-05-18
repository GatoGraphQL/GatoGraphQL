<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_ContentCreation_Module_Processor_FormComponentGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const MODULE_FORMCOMPONENTGROUP_CARD_FLAG = 'formcomponentgroup-card-flag';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMCOMPONENTGROUP_CARD_FLAG],
        );
    }

    public function getComponentSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FORMCOMPONENTGROUP_CARD_FLAG:
                return [PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::MODULE_FORMCOMPONENT_CARD_POST];
        }

        return parent::getComponentSubmodule($module);
    }

    public function initModelProps(array $module, array &$props): void
    {
        $moduleprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        switch ($module[1]) {
            case self::MODULE_FORMCOMPONENTGROUP_CARD_FLAG:
                $component = $this->getComponentSubmodule($module);

                $trigger = $moduleprocessor_manager->getProcessor($component)->getTriggerSubmodule($component);
                $description = sprintf(
                    '<em><label><strong>%s</strong></label></em>',
                    TranslationAPIFacade::getInstance()->__('Flag as inappropriate:', 'pop-application-processors')
                );
                $this->setProp($trigger, $props, 'description', $description);
                break;
        }

        parent::initModelProps($module, $props);
    }

    public function getLabel(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMCOMPONENTGROUP_CARD_FLAG:
                return '';
        }

        return parent::getLabel($module, $props);
    }
}



