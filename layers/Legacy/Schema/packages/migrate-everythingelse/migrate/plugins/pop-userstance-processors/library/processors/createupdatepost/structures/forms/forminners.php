<?php

class UserStance_Module_Processor_CreateUpdatePostFormInners extends Wassup_Module_Processor_CreateUpdatePostFormInnersBase
{
    public final const MODULE_FORMINNER_STANCE = 'forminner-stance';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINNER_STANCE],
        );
    }

    protected function getFeaturedimageInput(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINNER_STANCE:
                return null;
        }

        return parent::getFeaturedimageInput($module);
    }
    protected function getCoauthorsInput(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINNER_STANCE:
                return null;
        }

        return parent::getCoauthorsInput($module);
    }
    protected function getTitleInput(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINNER_STANCE:
                return null;
        }

        return parent::getTitleInput($module);
    }
    protected function getEditorInput(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINNER_STANCE:
                return [PoP_Module_Processor_TextareaFormInputs::class, PoP_Module_Processor_TextareaFormInputs::MODULE_FORMINPUT_TEXTAREAEDITOR];
        }

        return parent::getEditorInput($module);
    }
    protected function getStatusInput(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINNER_STANCE:
                // Stances are always published immediately, independently of value of GD_CONF_CREATEUPDATEPOST_MODERATE
                return [PoP_Module_Processor_CreateUpdatePostCheckboxFormInputs::class, PoP_Module_Processor_CreateUpdatePostCheckboxFormInputs::MODULE_FORMINPUT_CUP_KEEPASDRAFT];
        }

        return parent::getStatusInput($module);
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);
        
        switch ($module[1]) {
            case self::MODULE_FORMINNER_STANCE:
                return array_merge(
                    $ret,
                    array(
                        [UserStance_Module_Processor_FormMultipleComponents::class, UserStance_Module_Processor_FormMultipleComponents::MODULE_MULTICOMPONENT_FORM_STANCE_MAYBELEFTSIDE],
                        [UserStance_Module_Processor_FormMultipleComponents::class, UserStance_Module_Processor_FormMultipleComponents::MODULE_MULTICOMPONENT_FORM_STANCE_MAYBERIGHTSIDE],
                    )
                );
        }

        return parent::getComponentSubmodules($module, $props);
    }
}



