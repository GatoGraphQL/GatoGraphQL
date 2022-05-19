<?php

class PoP_Module_Processor_SettingsForms extends PoP_Module_Processor_FormsBase
{
    public final const COMPONENT_FORM_SETTINGS = 'form-settings';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORM_SETTINGS],
        );
    }

    public function getInnerSubcomponent(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORM_SETTINGS:
                return [PoP_Module_Processor_SettingsFormInners::class, PoP_Module_Processor_SettingsFormInners::COMPONENT_FORMINNER_SETTINGS];
        }

        return parent::getInnerSubcomponent($component);
    }
}



