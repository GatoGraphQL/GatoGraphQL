<?php

class PoP_LocationPostLinksCreation_Module_Processor_CreateUpdatePostForms extends PoP_Module_Processor_FormsBase
{
    public final const MODULE_FORM_LOCATIONPOSTLINK = 'form-locationpostlink';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [GD_Custom_EM_Module_Processor_CreateUpdatePostForms::class, GD_Custom_EM_Module_Processor_CreateUpdatePostForms::MODULE_FORM_LOCATIONPOSTLINK],
        );
    }

    public function getInnerSubmodule(array $componentVariation)
    {
        $inners = array(
            GD_Custom_EM_Module_Processor_CreateUpdatePostForms::MODULE_FORM_LOCATIONPOSTLINK => [GD_Custom_EM_Module_Processor_CreateUpdatePostFormInners::class, GD_Custom_EM_Module_Processor_CreateUpdatePostFormInners::MODULE_FORMINNER_LOCATIONPOSTLINK],
        );

        if ($inner = $inners[$componentVariation[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($componentVariation);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORM_LOCATIONPOSTLINK:
                // Allow to override the classes, so it can be set for the Addons pageSection without the col-sm styles, but one on top of the other
                if (!($form_row_classs = $this->getProp($componentVariation, $props, 'form-row-class')/*$this->get_general_prop($props, 'form-row-class')*/)) {
                    $form_row_classs = 'row';
                }
                $this->appendProp($componentVariation, $props, 'class', $form_row_classs);
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



