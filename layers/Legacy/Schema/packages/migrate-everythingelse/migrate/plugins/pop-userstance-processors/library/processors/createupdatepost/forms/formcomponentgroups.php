<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class UserStance_Module_Processor_FormComponentGroupsGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const MODULE_FORMCOMPONENTGROUP_CARD_STANCETARGET = 'formcomponentgroup-card-stancetarget';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMCOMPONENTGROUP_CARD_STANCETARGET],
        );
    }

    public function getComponentSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FORMCOMPONENTGROUP_CARD_STANCETARGET:
                return [PoP_UserStance_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_UserStance_Module_Processor_PostTriggerLayoutFormComponentValues::MODULE_FORMCOMPONENT_CARD_STANCETARGET];
        }

        return parent::getComponentSubmodule($module);
    }

    public function initModelProps(array $module, array &$props): void
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        switch ($module[1]) {
            case self::MODULE_FORMCOMPONENTGROUP_CARD_STANCETARGET:
                $this->appendProp($module, $props, 'class', 'pop-uniqueornone-selectabletypeahead-formgroup');

                $component = $this->getComponentSubmodule($module);

                $trigger = $componentprocessor_manager->getProcessor($component)->getTriggerSubmodule($component);
                $description = sprintf(
                    '<em><label><strong>%s</strong></label></em>',
                    TranslationAPIFacade::getInstance()->__('After reading...', 'pop-userstance-processors')
                );
                $this->setProp($trigger, $props, 'description', $description);
                break;
        }

        parent::initModelProps($module, $props);
    }

    public function getLabel(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMCOMPONENTGROUP_CARD_STANCETARGET:
                return '';
        }

        return parent::getLabel($module, $props);
    }
}



