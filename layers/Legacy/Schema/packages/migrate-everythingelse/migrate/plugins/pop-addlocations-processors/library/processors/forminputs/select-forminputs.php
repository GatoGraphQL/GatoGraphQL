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

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_EM_LOCATIONCOUNTRY:
                return TranslationAPIFacade::getInstance()->__('Country', 'em-popprocessors');
        }

        return parent::getLabelText($module, $props);
    }

    public function getInputClass(array $module): string
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_EM_LOCATIONCOUNTRY:
                return GD_FormInput_EM_LocationCountries::class;
        }

        return parent::getInputClass($module);
    }

    // function getName(array $module) {

    //     switch ($module[1]) {

    //          // Names needed by EM to create the Location
    //         case self::MODULE_FORMINPUT_EM_LOCATIONCOUNTRY:

    //             return 'location_country';
    //     }

    //     return parent::getName($module);
    // }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_EM_LOCATIONCOUNTRY:
                $this->appendProp($module, $props, 'class', 'address-input');
                break;
        }

        parent::initModelProps($module, $props);
    }
}



