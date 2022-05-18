<?php

class UserStance_Module_Processor_CreateUpdatePostFormInners extends Wassup_Module_Processor_CreateUpdatePostFormInnersBase
{
    public final const COMPONENT_FORMINNER_STANCE = 'forminner-stance';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINNER_STANCE],
        );
    }

    protected function getFeaturedimageInput(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINNER_STANCE:
                return null;
        }

        return parent::getFeaturedimageInput($component);
    }
    protected function getCoauthorsInput(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINNER_STANCE:
                return null;
        }

        return parent::getCoauthorsInput($component);
    }
    protected function getTitleInput(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINNER_STANCE:
                return null;
        }

        return parent::getTitleInput($component);
    }
    protected function getEditorInput(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINNER_STANCE:
                return [PoP_Module_Processor_TextareaFormInputs::class, PoP_Module_Processor_TextareaFormInputs::COMPONENT_FORMINPUT_TEXTAREAEDITOR];
        }

        return parent::getEditorInput($component);
    }
    protected function getStatusInput(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINNER_STANCE:
                // Stances are always published immediately, independently of value of GD_CONF_CREATEUPDATEPOST_MODERATE
                return [PoP_Module_Processor_CreateUpdatePostCheckboxFormInputs::class, PoP_Module_Processor_CreateUpdatePostCheckboxFormInputs::COMPONENT_FORMINPUT_CUP_KEEPASDRAFT];
        }

        return parent::getStatusInput($component);
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);
        
        switch ($component[1]) {
            case self::COMPONENT_FORMINNER_STANCE:
                return array_merge(
                    $ret,
                    array(
                        [UserStance_Module_Processor_FormMultipleComponents::class, UserStance_Module_Processor_FormMultipleComponents::COMPONENT_MULTICOMPONENT_FORM_STANCE_MAYBELEFTSIDE],
                        [UserStance_Module_Processor_FormMultipleComponents::class, UserStance_Module_Processor_FormMultipleComponents::COMPONENT_MULTICOMPONENT_FORM_STANCE_MAYBERIGHTSIDE],
                    )
                );
        }

        return parent::getComponentSubmodules($component, $props);
    }
}



