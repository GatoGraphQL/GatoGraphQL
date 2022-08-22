<?php

class PoP_Module_Processor_CreateOrUpdateStanceButtonScriptFrameLayouts extends PoP_Module_Processor_CreateOrUpdateStanceButtonScriptFrameLayoutsBase
{
    public final const COMPONENT_BUTTON_STANCE_CREATEORUPDATE_APPENDTOSCRIPT = 'postbutton-stance-createorupdate-appendtoscript';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_BUTTON_STANCE_CREATEORUPDATE_APPENDTOSCRIPT,
        );
    }

    public function getLayoutSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_BUTTON_STANCE_CREATEORUPDATE_APPENDTOSCRIPT:
                return [UserStance_Module_Processor_WidgetWrappers::class, UserStance_Module_Processor_WidgetWrappers::COMPONENT_BUTTONWRAPPER_STANCE_CREATEORUPDATE];
        }

        return parent::getLayoutSubcomponent($component);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_BUTTON_STANCE_CREATEORUPDATE_APPENDTOSCRIPT:
                $this->appendProp($component, $props, 'class', 'inline');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



