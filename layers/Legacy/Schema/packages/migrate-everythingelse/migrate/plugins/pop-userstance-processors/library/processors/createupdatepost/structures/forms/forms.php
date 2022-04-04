<?php

class UserStance_Module_Processor_CreateUpdatePostForms extends PoP_Module_Processor_FormsBase
{
    public final const MODULE_FORM_STANCE = 'form-stance';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORM_STANCE],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_FORM_STANCE => [UserStance_Module_Processor_CreateUpdatePostFormInners::class, UserStance_Module_Processor_CreateUpdatePostFormInners::MODULE_FORMINNER_STANCE],
        );

        if ($inner = $inners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_FORM_STANCE:
                // Make it horizontal? If set by above (most likely the block)
                if ($this->getProp($module, $props, 'horizontal')) {
                    $this->appendProp($module, $props, 'class', 'row');
                    $this->appendProp([UserStance_Module_Processor_FormMultipleComponents::class, UserStance_Module_Processor_FormMultipleComponents::MODULE_MULTICOMPONENT_FORM_STANCE_MAYBELEFTSIDE], $props, 'class', 'col-sm-8');
                    $this->appendProp([UserStance_Module_Processor_FormMultipleComponents::class, UserStance_Module_Processor_FormMultipleComponents::MODULE_MULTICOMPONENT_FORM_STANCE_MAYBERIGHTSIDE], $props, 'class', 'col-sm-4');
                }
                break;
        }

        parent::initModelProps($module, $props);
    }
}



