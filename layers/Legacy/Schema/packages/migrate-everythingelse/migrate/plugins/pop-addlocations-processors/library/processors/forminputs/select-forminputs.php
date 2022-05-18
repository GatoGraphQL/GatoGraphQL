<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_EM_Module_Processor_CreateLocationSelectFormInputs extends PoP_Module_Processor_SelectFormInputsBase
{
    public final const COMPONENT_FORMINPUT_EM_LOCATIONCOUNTRY = 'forminput-locationcountry';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINPUT_EM_LOCATIONCOUNTRY],
        );
    }

    public function getLabelText(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUT_EM_LOCATIONCOUNTRY:
                return TranslationAPIFacade::getInstance()->__('Country', 'em-popprocessors');
        }

        return parent::getLabelText($component, $props);
    }

    public function getInputClass(array $component): string
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUT_EM_LOCATIONCOUNTRY:
                return GD_FormInput_EM_LocationCountries::class;
        }

        return parent::getInputClass($component);
    }

    // function getName(array $component) {

    //     switch ($component[1]) {

    //          // Names needed by EM to create the Location
    //         case self::COMPONENT_FORMINPUT_EM_LOCATIONCOUNTRY:

    //             return 'location_country';
    //     }

    //     return parent::getName($component);
    // }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUT_EM_LOCATIONCOUNTRY:
                $this->appendProp($component, $props, 'class', 'address-input');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



