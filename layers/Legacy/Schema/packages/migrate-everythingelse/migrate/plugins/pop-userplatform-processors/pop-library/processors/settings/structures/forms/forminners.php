<?php

class PoP_Module_Processor_SettingsFormInners extends PoP_Module_Processor_FormInnersBase
{
    public const MODULE_FORMINNER_SETTINGS = 'forminner-settings';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINNER_SETTINGS],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);
    
        switch ($module[1]) {
            case self::MODULE_FORMINNER_SETTINGS:
                $ret[] = [PoP_Module_Processor_TextFormInputs::class, PoP_Module_Processor_TextFormInputs::MODULE_FORMINPUT_BROWSERURL];
                
                if (defined('POP_MULTILINGUALPROCESSORS_INITIALIZED')) {
                    $pluginapi = PoP_Multilingual_FunctionsAPIFactory::getInstance();
                    $languages = $pluginapi->getEnabledLanguages();
                    if ($languages && count($languages) > 1) {
                        $ret[] = [GD_QT_Module_Processor_FormGroups::class, GD_QT_Module_Processor_FormGroups::MODULE_QT_FORMINPUTGROUP_LANGUAGE];
                    }
                }
                $ret[] = [GD_UserPlatform_Module_Processor_FormInputGroups::class, GD_UserPlatform_Module_Processor_FormInputGroups::MODULE_FORMINPUTGROUP_SETTINGSFORMAT];
                $ret[] = [PoP_Module_Processor_SubmitButtons::class, PoP_Module_Processor_SubmitButtons::MODULE_SUBMITBUTTON_OK];
                break;
        }

        return $ret;
    }
}



