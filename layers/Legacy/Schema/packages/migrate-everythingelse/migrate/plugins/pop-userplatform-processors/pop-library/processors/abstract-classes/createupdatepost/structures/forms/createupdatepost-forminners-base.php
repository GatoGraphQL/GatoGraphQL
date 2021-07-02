<?php

abstract class Wassup_Module_Processor_CreateUpdatePostFormInnersBase extends PoP_Module_Processor_CreateUpdatePostFormInnersBase
{
    protected function getReferencesInput(array $module)
    {
        return [PoP_Module_Processor_PostSelectableTypeaheadFormComponents::class, PoP_Module_Processor_PostSelectableTypeaheadFormComponents::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES];
    }
    
    protected function volunteering(array $module)
    {
        return false;
    }

    protected function getLocationsInput(array $module)
    {
        return null;
    }

    protected function isLink(array $module)
    {
        return false;
    }

    protected function getCategoriesModule(array $module)
    {
        return [PoP_Module_Processor_CreateUpdatePostMultiSelectFormInputs::class, PoP_Module_Processor_CreateUpdatePostMultiSelectFormInputs::MODULE_FORMINPUT_CATEGORIES];
    }

    protected function getAppliestoInput(array $module)
    {
        return [PoP_Module_Processor_CreateUpdatePostMultiSelectFormInputs::class, PoP_Module_Processor_CreateUpdatePostMultiSelectFormInputs::MODULE_FORMINPUT_APPLIESTO];
    }
}
