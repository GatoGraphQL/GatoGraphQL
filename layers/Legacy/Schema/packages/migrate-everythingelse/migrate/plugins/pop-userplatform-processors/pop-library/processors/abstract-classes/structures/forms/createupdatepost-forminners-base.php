<?php

abstract class PoP_Module_Processor_CreateUpdatePostFormInnersBase extends PoP_Module_Processor_FormInnersBase
{
    protected function getFeaturedimageInput(array $component)
    {
        return [PoP_Module_Processor_FeaturedImageFormComponents::class, PoP_Module_Processor_FeaturedImageFormComponents::COMPONENT_FORMCOMPONENT_FEATUREDIMAGE];
    }
    protected function getCoauthorsInput(array $component)
    {
        if (defined('POP_COAUTHORSPROCESSORS_INITIALIZED')) {
            return [GD_CAP_Module_Processor_UserSelectableTypeaheadFormInputs::class, GD_CAP_Module_Processor_UserSelectableTypeaheadFormInputs::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTCOAUTHORS];
        }
        return null;
    }
    protected function getTitleInput(array $component)
    {
        return [PoP_Module_Processor_CreateUpdatePostTextFormInputs::class, PoP_Module_Processor_CreateUpdatePostTextFormInputs::COMPONENT_FORMINPUT_CUP_TITLE];
    }
    protected function getEditorInput(array $component)
    {
        return [PoP_Module_Processor_EditorFormInputs::class, PoP_Module_Processor_EditorFormInputs::COMPONENT_FORMINPUT_EDITOR];
    }
    protected function getStatusInput(array $component)
    {
        if (!GD_CreateUpdate_Utils::moderate()) {
            return [PoP_Module_Processor_CreateUpdatePostCheckboxFormInputs::class, PoP_Module_Processor_CreateUpdatePostCheckboxFormInputs::COMPONENT_FORMINPUT_CUP_KEEPASDRAFT];
        }

        return [PoP_Module_Processor_CreateUpdatePostSelectFormInputs::class, PoP_Module_Processor_CreateUpdatePostSelectFormInputs::COMPONENT_FORMINPUT_CUP_STATUS];
    }
    protected function getEditorInitialvalue(array $component)
    {
        return null;
    }

    public function initModelProps(array $component, array &$props): void
    {

        // Set an initial value?
        if ($initialvalue = $this->getEditorInitialvalue($component)) {
            $editor = $this->getEditorInput($component);
            $this->setProp($editor, $props, 'default-value', $initialvalue);
        }

        parent::initModelProps($component, $props);
    }
}
