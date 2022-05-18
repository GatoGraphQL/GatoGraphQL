<?php

class PoP_Module_Processor_FetchlinkTypeaheadFormComponents extends PoP_Module_Processor_FetchlinkTypeaheadFormComponentsBase
{
    public final const MODULE_FORMCOMPONENT_QUICKLINKTYPEAHEAD_EVERYTHING = 'formcomponent-quicklinktypeahead-everything';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMCOMPONENT_QUICKLINKTYPEAHEAD_EVERYTHING],
        );
    }

    public function getComponentSubmodules(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMCOMPONENT_QUICKLINKTYPEAHEAD_EVERYTHING:
                return array(
                    [PoP_Module_Processor_UserTypeaheadComponentFormInputs::class, PoP_Module_Processor_UserTypeaheadComponentFormInputs::MODULE_TYPEAHEAD_COMPONENT_USERS],
                    [PoP_Module_Processor_PostTypeaheadComponentFormInputs::class, PoP_Module_Processor_PostTypeaheadComponentFormInputs::MODULE_TYPEAHEAD_COMPONENT_CONTENT],
                    [PoP_Module_Processor_TagTypeaheadComponentFormInputs::class, PoP_Module_Processor_TagTypeaheadComponentFormInputs::MODULE_TYPEAHEAD_COMPONENT_TAGS],
                    [PoP_Module_Processor_StaticTypeaheadComponentFormInputs::class, PoP_Module_Processor_StaticTypeaheadComponentFormInputs::MODULE_TYPEAHEAD_COMPONENT_STATICSEARCH],
                );
        }

        return parent::getComponentSubmodules($componentVariation);
    }

    public function getInputSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMCOMPONENT_QUICKLINKTYPEAHEAD_EVERYTHING:
                return [PoP_Module_Processor_InputGroupFormComponents::class, PoP_Module_Processor_InputGroupFormComponents::MODULE_FORMCOMPONENT_INPUTGROUP_TYPEAHEADSEARCH];
        }

        return parent::getInputSubmodule($componentVariation);
    }
}



