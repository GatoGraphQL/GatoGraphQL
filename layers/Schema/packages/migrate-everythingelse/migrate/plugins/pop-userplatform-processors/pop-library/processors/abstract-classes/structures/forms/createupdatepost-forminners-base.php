<?php

abstract class PoP_Module_Processor_CreateUpdatePostFormInnersBase extends PoP_Module_Processor_FormInnersBase
{
    protected function getFeaturedimageInput(array $module)
    {
        return [PoP_Module_Processor_FeaturedImageFormComponents::class, PoP_Module_Processor_FeaturedImageFormComponents::MODULE_FORMCOMPONENT_FEATUREDIMAGE];
    }
    protected function getCoauthorsInput(array $module)
    {
        if (defined('POP_COAUTHORSPROCESSORS_INITIALIZED')) {
            return [GD_CAP_Module_Processor_UserSelectableTypeaheadFormInputs::class, GD_CAP_Module_Processor_UserSelectableTypeaheadFormInputs::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTCOAUTHORS];
        }
        return null;
    }
    protected function getTitleInput(array $module)
    {
        return [PoP_Module_Processor_CreateUpdatePostTextFormInputs::class, PoP_Module_Processor_CreateUpdatePostTextFormInputs::MODULE_FORMINPUT_CUP_TITLE];
    }
    protected function getEditorInput(array $module)
    {
        return [PoP_Module_Processor_EditorFormInputs::class, PoP_Module_Processor_EditorFormInputs::MODULE_FORMINPUT_EDITOR];
    }
    protected function getStatusInput(array $module)
    {
        if (!GD_CreateUpdate_Utils::moderate()) {
            return [PoP_Module_Processor_CreateUpdatePostCheckboxFormInputs::class, PoP_Module_Processor_CreateUpdatePostCheckboxFormInputs::MODULE_FORMINPUT_CUP_KEEPASDRAFT];
        }
        
        return [PoP_Module_Processor_CreateUpdatePostSelectFormInputs::class, PoP_Module_Processor_CreateUpdatePostSelectFormInputs::MODULE_FORMINPUT_CUP_STATUS];
    }
    protected function getEditorInitialvalue(array $module)
    {
        return null;
    }

    public function initModelProps(array $module, array &$props)
    {

        // Set an initial value?
        if ($initialvalue = $this->getEditorInitialvalue($module)) {
            $editor = $this->getEditorInput($module);
            $this->setProp($editor, $props, 'default-value', $initialvalue);
        }
        
        parent::initModelProps($module, $props);
    }
}
