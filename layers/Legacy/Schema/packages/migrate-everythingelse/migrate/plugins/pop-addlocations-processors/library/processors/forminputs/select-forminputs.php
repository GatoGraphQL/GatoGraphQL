<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_EM_Module_Processor_CreateLocationSelectFormInputs extends PoP_Module_Processor_SelectFormInputsBase
{
    public final const COMPONENT_FORMINPUT_EM_LOCATIONCOUNTRY = 'forminput-locationcountry';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMINPUT_EM_LOCATIONCOUNTRY,
        );
    }

    public function getLabelText(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_EM_LOCATIONCOUNTRY:
                return TranslationAPIFacade::getInstance()->__('Country', 'em-popprocessors');
        }

        return parent::getLabelText($component, $props);
    }

    public function getInputClass(\PoP\ComponentModel\Component\Component $component): string
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_EM_LOCATIONCOUNTRY:
                return GD_FormInput_EM_LocationCountries::class;
        }

        return parent::getInputClass($component);
    }

    // function getName(\PoP\ComponentModel\Component\Component $component) {

    //     switch ($component->name) {

    //          // Names needed by EM to create the Location
    //         case self::COMPONENT_FORMINPUT_EM_LOCATIONCOUNTRY:

    //             return 'location_country';
    //     }

    //     return parent::getName($component);
    // }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_EM_LOCATIONCOUNTRY:
                $this->appendProp($component, $props, 'class', 'address-input');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



