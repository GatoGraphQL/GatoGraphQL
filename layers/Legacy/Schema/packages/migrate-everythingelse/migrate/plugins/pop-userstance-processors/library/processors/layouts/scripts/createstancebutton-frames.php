<?php

class PoP_Module_Processor_CreateOrUpdateStanceButtonScriptFrameLayouts extends PoP_Module_Processor_CreateOrUpdateStanceButtonScriptFrameLayoutsBase
{
    public final const MODULE_BUTTON_STANCE_CREATEORUPDATE_APPENDTOSCRIPT = 'postbutton-stance-createorupdate-appendtoscript';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BUTTON_STANCE_CREATEORUPDATE_APPENDTOSCRIPT],
        );
    }

    public function getLayoutSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BUTTON_STANCE_CREATEORUPDATE_APPENDTOSCRIPT:
                return [UserStance_Module_Processor_WidgetWrappers::class, UserStance_Module_Processor_WidgetWrappers::MODULE_BUTTONWRAPPER_STANCE_CREATEORUPDATE];
        }

        return parent::getLayoutSubmodule($componentVariation);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BUTTON_STANCE_CREATEORUPDATE_APPENDTOSCRIPT:
                $this->appendProp($componentVariation, $props, 'class', 'inline');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



