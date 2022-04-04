<?php

class PoP_Module_Processor_CreateOrUpdateStanceButtonScriptFrameLayouts extends PoP_Module_Processor_CreateOrUpdateStanceButtonScriptFrameLayoutsBase
{
    public final const MODULE_BUTTON_STANCE_CREATEORUPDATE_APPENDTOSCRIPT = 'postbutton-stance-createorupdate-appendtoscript';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BUTTON_STANCE_CREATEORUPDATE_APPENDTOSCRIPT],
        );
    }

    public function getLayoutSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BUTTON_STANCE_CREATEORUPDATE_APPENDTOSCRIPT:
                return [UserStance_Module_Processor_WidgetWrappers::class, UserStance_Module_Processor_WidgetWrappers::MODULE_BUTTONWRAPPER_STANCE_CREATEORUPDATE];
        }

        return parent::getLayoutSubmodule($module);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_BUTTON_STANCE_CREATEORUPDATE_APPENDTOSCRIPT:
                $this->appendProp($module, $props, 'class', 'inline');
                break;
        }

        parent::initModelProps($module, $props);
    }
}



