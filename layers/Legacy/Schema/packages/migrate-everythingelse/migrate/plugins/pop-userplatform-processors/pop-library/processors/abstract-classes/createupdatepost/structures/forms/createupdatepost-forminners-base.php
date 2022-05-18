<?php

abstract class Wassup_Module_Processor_CreateUpdatePostFormInnersBase extends PoP_Module_Processor_CreateUpdatePostFormInnersBase
{
    protected function getReferencesInput(array $componentVariation)
    {
        return [PoP_Module_Processor_PostSelectableTypeaheadFormComponents::class, PoP_Module_Processor_PostSelectableTypeaheadFormComponents::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES];
    }
    
    protected function volunteering(array $componentVariation)
    {
        return false;
    }

    protected function getLocationsInput(array $componentVariation)
    {
        return null;
    }

    protected function isLink(array $componentVariation)
    {
        return false;
    }

    protected function getCategoriesModule(array $componentVariation)
    {
        return [PoP_Module_Processor_CreateUpdatePostMultiSelectFormInputs::class, PoP_Module_Processor_CreateUpdatePostMultiSelectFormInputs::MODULE_FORMINPUT_CATEGORIES];
    }

    protected function getAppliestoInput(array $componentVariation)
    {
        return [PoP_Module_Processor_CreateUpdatePostMultiSelectFormInputs::class, PoP_Module_Processor_CreateUpdatePostMultiSelectFormInputs::MODULE_FORMINPUT_APPLIESTO];
    }
}
