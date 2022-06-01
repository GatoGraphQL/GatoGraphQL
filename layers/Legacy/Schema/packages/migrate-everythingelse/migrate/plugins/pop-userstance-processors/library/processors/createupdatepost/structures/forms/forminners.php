<?php

class UserStance_Module_Processor_CreateUpdatePostFormInners extends Wassup_Module_Processor_CreateUpdatePostFormInnersBase
{
    public final const COMPONENT_FORMINNER_STANCE = 'forminner-stance';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMINNER_STANCE,
        );
    }

    protected function getFeaturedimageInput(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINNER_STANCE:
                return null;
        }

        return parent::getFeaturedimageInput($component);
    }
    protected function getCoauthorsInput(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINNER_STANCE:
                return null;
        }

        return parent::getCoauthorsInput($component);
    }
    protected function getTitleInput(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINNER_STANCE:
                return null;
        }

        return parent::getTitleInput($component);
    }
    protected function getEditorInput(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINNER_STANCE:
                return [PoP_Module_Processor_TextareaFormInputs::class, PoP_Module_Processor_TextareaFormInputs::COMPONENT_FORMINPUT_TEXTAREAEDITOR];
        }

        return parent::getEditorInput($component);
    }
    protected function getStatusInput(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINNER_STANCE:
                // Stances are always published immediately, independently of value of GD_CONF_CREATEUPDATEPOST_MODERATE
                return [PoP_Module_Processor_CreateUpdatePostCheckboxFormInputs::class, PoP_Module_Processor_CreateUpdatePostCheckboxFormInputs::COMPONENT_FORMINPUT_CUP_KEEPASDRAFT];
        }

        return parent::getStatusInput($component);
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getLayoutSubcomponents($component);
        
        switch ($component->name) {
            case self::COMPONENT_FORMINNER_STANCE:
                return array_merge(
                    $ret,
                    array(
                        [UserStance_Module_Processor_FormMultipleComponents::class, UserStance_Module_Processor_FormMultipleComponents::COMPONENT_MULTICOMPONENT_FORM_STANCE_MAYBELEFTSIDE],
                        [UserStance_Module_Processor_FormMultipleComponents::class, UserStance_Module_Processor_FormMultipleComponents::COMPONENT_MULTICOMPONENT_FORM_STANCE_MAYBERIGHTSIDE],
                    )
                );
        }

        return $ret;
    }
}



