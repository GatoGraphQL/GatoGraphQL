<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_EM_Module_Processor_CreateLocationSelectFormInputs extends PoP_Module_Processor_SelectFormInputsBase
{
    public final const MODULE_FORMINPUT_EM_LOCATIONCOUNTRY = 'forminput-locationcountry';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_EM_LOCATIONCOUNTRY],
        );
    }

    public function getLabelText(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_EM_LOCATIONCOUNTRY:
                return TranslationAPIFacade::getInstance()->__('Country', 'em-popprocessors');
        }

        return parent::getLabelText($componentVariation, $props);
    }

    public function getInputClass(array $componentVariation): string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_EM_LOCATIONCOUNTRY:
                return GD_FormInput_EM_LocationCountries::class;
        }

        return parent::getInputClass($componentVariation);
    }

    // function getName(array $componentVariation) {

    //     switch ($componentVariation[1]) {

    //          // Names needed by EM to create the Location
    //         case self::MODULE_FORMINPUT_EM_LOCATIONCOUNTRY:

    //             return 'location_country';
    //     }

    //     return parent::getName($componentVariation);
    // }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_EM_LOCATIONCOUNTRY:
                $this->appendProp($componentVariation, $props, 'class', 'address-input');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



