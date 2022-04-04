<?php

class GD_Custom_EM_Module_Processor_CreateUpdatePostForms extends PoP_Module_Processor_FormsBase
{
    public final const MODULE_FORM_LOCATIONPOST = 'form-locationpost';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORM_LOCATIONPOST],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_FORM_LOCATIONPOST => [GD_Custom_EM_Module_Processor_CreateUpdatePostFormInners::class, GD_Custom_EM_Module_Processor_CreateUpdatePostFormInners::MODULE_FORMINNER_LOCATIONPOST],
        );

        if ($inner = $inners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_FORM_LOCATIONPOST:
                // Allow to override the classes, so it can be set for the Addons pageSection without the col-sm styles, but one on top of the other
                if (!($form_row_classs = $this->getProp($module, $props, 'form-row-class')/*$this->get_general_prop($props, 'form-row-class')*/)) {
                    $form_row_classs = 'row';
                }
                $this->appendProp($module, $props, 'class', $form_row_classs);
                break;
        }

        parent::initModelProps($module, $props);
    }
}



