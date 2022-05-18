<?php

class PoP_Module_Processor_SettingsFormInners extends PoP_Module_Processor_FormInnersBase
{
    public final const MODULE_FORMINNER_SETTINGS = 'forminner-settings';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINNER_SETTINGS],
        );
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);
    
        switch ($component[1]) {
            case self::COMPONENT_FORMINNER_SETTINGS:
                $ret[] = [PoP_Module_Processor_TextFormInputs::class, PoP_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_BROWSERURL];
                
                if (defined('POP_MULTILINGUALPROCESSORS_INITIALIZED')) {
                    $pluginapi = PoP_Multilingual_FunctionsAPIFactory::getInstance();
                    $languages = $pluginapi->getEnabledLanguages();
                    if ($languages && count($languages) > 1) {
                        $ret[] = [GD_QT_Module_Processor_FormGroups::class, GD_QT_Module_Processor_FormGroups::COMPONENT_QT_FORMINPUTGROUP_LANGUAGE];
                    }
                }
                $ret[] = [GD_UserPlatform_Module_Processor_FormInputGroups::class, GD_UserPlatform_Module_Processor_FormInputGroups::COMPONENT_FORMINPUTGROUP_SETTINGSFORMAT];
                $ret[] = [PoP_Module_Processor_SubmitButtons::class, PoP_Module_Processor_SubmitButtons::COMPONENT_SUBMITBUTTON_OK];
                break;
        }

        return $ret;
    }
}



