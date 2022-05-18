<?php

class UserStance_Module_Processor_CreateUpdatePostForms extends PoP_Module_Processor_FormsBase
{
    public final const MODULE_FORM_STANCE = 'form-stance';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORM_STANCE],
        );
    }

    public function getInnerSubmodule(array $component)
    {
        $inners = array(
            self::COMPONENT_FORM_STANCE => [UserStance_Module_Processor_CreateUpdatePostFormInners::class, UserStance_Module_Processor_CreateUpdatePostFormInners::COMPONENT_FORMINNER_STANCE],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_FORM_STANCE:
                // Make it horizontal? If set by above (most likely the block)
                if ($this->getProp($component, $props, 'horizontal')) {
                    $this->appendProp($component, $props, 'class', 'row');
                    $this->appendProp([UserStance_Module_Processor_FormMultipleComponents::class, UserStance_Module_Processor_FormMultipleComponents::COMPONENT_MULTICOMPONENT_FORM_STANCE_MAYBELEFTSIDE], $props, 'class', 'col-sm-8');
                    $this->appendProp([UserStance_Module_Processor_FormMultipleComponents::class, UserStance_Module_Processor_FormMultipleComponents::COMPONENT_MULTICOMPONENT_FORM_STANCE_MAYBERIGHTSIDE], $props, 'class', 'col-sm-4');
                }
                break;
        }

        parent::initModelProps($component, $props);
    }
}



