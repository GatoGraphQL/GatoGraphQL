<?php

class PoP_EventLinksCreation_Module_Processor_CreateUpdatePostForms extends PoP_Module_Processor_FormsBase
{
    public final const MODULE_FORM_EVENTLINK = 'form-eventlink';

    public function getComponentsToProcess(): array
    {
        return array(
            [GD_EM_Module_Processor_CreateUpdatePostForms::class, GD_EM_Module_Processor_CreateUpdatePostForms::COMPONENT_FORM_EVENTLINK],
        );
    }

    public function getInnerSubmodule(array $component)
    {
        $inners = array(
            GD_EM_Module_Processor_CreateUpdatePostForms::COMPONENT_FORM_EVENTLINK => [GD_EM_Module_Processor_CreateUpdatePostFormInners::class, GD_EM_Module_Processor_CreateUpdatePostFormInners::COMPONENT_FORMINNER_EVENTLINK],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_FORM_EVENTLINK:
                // Allow to override the classes, so it can be set for the Addons pageSection without the col-sm styles, but one on top of the other
                if (!($form_row_classs = $this->getProp($component, $props, 'form-row-class')/*$this->get_general_prop($props, 'form-row-class')*/)) {
                    $form_row_classs = 'row';
                }
                $this->appendProp($component, $props, 'class', $form_row_classs);
                break;
        }

        parent::initModelProps($component, $props);
    }
}



