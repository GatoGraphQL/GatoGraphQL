<?php

class PoP_Module_Processor_FetchlinkTypeaheadFormComponents extends PoP_Module_Processor_FetchlinkTypeaheadFormComponentsBase
{
    public final const COMPONENT_FORMCOMPONENT_QUICKLINKTYPEAHEAD_EVERYTHING = 'formcomponent-quicklinktypeahead-everything';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMCOMPONENT_QUICKLINKTYPEAHEAD_EVERYTHING,
        );
    }

    public function getComponentSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMCOMPONENT_QUICKLINKTYPEAHEAD_EVERYTHING:
                return array(
                    [PoP_Module_Processor_UserTypeaheadComponentFormInputs::class, PoP_Module_Processor_UserTypeaheadComponentFormInputs::COMPONENT_TYPEAHEAD_COMPONENT_USERS],
                    [PoP_Module_Processor_PostTypeaheadComponentFormInputs::class, PoP_Module_Processor_PostTypeaheadComponentFormInputs::COMPONENT_TYPEAHEAD_COMPONENT_CONTENT],
                    [PoP_Module_Processor_TagTypeaheadComponentFormInputs::class, PoP_Module_Processor_TagTypeaheadComponentFormInputs::COMPONENT_TYPEAHEAD_COMPONENT_TAGS],
                    [PoP_Module_Processor_StaticTypeaheadComponentFormInputs::class, PoP_Module_Processor_StaticTypeaheadComponentFormInputs::COMPONENT_TYPEAHEAD_COMPONENT_STATICSEARCH],
                );
        }

        return parent::getComponentSubcomponents($component);
    }

    public function getInputSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMCOMPONENT_QUICKLINKTYPEAHEAD_EVERYTHING:
                return [PoP_Module_Processor_InputGroupFormComponents::class, PoP_Module_Processor_InputGroupFormComponents::COMPONENT_FORMCOMPONENT_INPUTGROUP_TYPEAHEADSEARCH];
        }

        return parent::getInputSubcomponent($component);
    }
}



