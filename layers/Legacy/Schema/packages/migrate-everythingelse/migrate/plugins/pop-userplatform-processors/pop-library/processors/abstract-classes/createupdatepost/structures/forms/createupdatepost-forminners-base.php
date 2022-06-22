<?php

abstract class Wassup_Module_Processor_CreateUpdatePostFormInnersBase extends PoP_Module_Processor_CreateUpdatePostFormInnersBase
{
    protected function getReferencesInput(\PoP\ComponentModel\Component\Component $component)
    {
        return [PoP_Module_Processor_PostSelectableTypeaheadFormComponents::class, PoP_Module_Processor_PostSelectableTypeaheadFormComponents::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES];
    }
    
    protected function volunteering(\PoP\ComponentModel\Component\Component $component)
    {
        return false;
    }

    protected function getLocationsInput(\PoP\ComponentModel\Component\Component $component)
    {
        return null;
    }

    protected function isLink(\PoP\ComponentModel\Component\Component $component)
    {
        return false;
    }

    protected function getCategoriesComponent(\PoP\ComponentModel\Component\Component $component)
    {
        return [PoP_Module_Processor_CreateUpdatePostMultiSelectFormInputs::class, PoP_Module_Processor_CreateUpdatePostMultiSelectFormInputs::COMPONENT_FORMINPUT_CATEGORIES];
    }

    protected function getAppliestoInput(\PoP\ComponentModel\Component\Component $component)
    {
        return [PoP_Module_Processor_CreateUpdatePostMultiSelectFormInputs::class, PoP_Module_Processor_CreateUpdatePostMultiSelectFormInputs::COMPONENT_FORMINPUT_APPLIESTO];
    }
}
