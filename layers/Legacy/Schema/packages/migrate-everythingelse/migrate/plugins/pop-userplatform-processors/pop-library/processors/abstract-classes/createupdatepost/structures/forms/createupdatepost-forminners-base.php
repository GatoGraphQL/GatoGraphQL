<?php

abstract class Wassup_Module_Processor_CreateUpdatePostFormInnersBase extends PoP_Module_Processor_CreateUpdatePostFormInnersBase
{
    protected function getReferencesInput(array $component)
    {
        return [PoP_Module_Processor_PostSelectableTypeaheadFormComponents::class, PoP_Module_Processor_PostSelectableTypeaheadFormComponents::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES];
    }
    
    protected function volunteering(array $component)
    {
        return false;
    }

    protected function getLocationsInput(array $component)
    {
        return null;
    }

    protected function isLink(array $component)
    {
        return false;
    }

    protected function getCategoriesComponent(array $component)
    {
        return [PoP_Module_Processor_CreateUpdatePostMultiSelectFormInputs::class, PoP_Module_Processor_CreateUpdatePostMultiSelectFormInputs::COMPONENT_FORMINPUT_CATEGORIES];
    }

    protected function getAppliestoInput(array $component)
    {
        return [PoP_Module_Processor_CreateUpdatePostMultiSelectFormInputs::class, PoP_Module_Processor_CreateUpdatePostMultiSelectFormInputs::COMPONENT_FORMINPUT_APPLIESTO];
    }
}
