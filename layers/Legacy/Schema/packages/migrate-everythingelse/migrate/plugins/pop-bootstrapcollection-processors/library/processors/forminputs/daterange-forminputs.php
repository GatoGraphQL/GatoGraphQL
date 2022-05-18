<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_DateRangeComponentInputs extends PoP_Module_Processor_DateRangeFormInputsBase
{
    public final const MODULE_FORMINPUT_DATERANGEPICKER = 'forminput-daterangepicker';
    public final const MODULE_FORMINPUT_DATERANGETIMEPICKER = 'forminput-daterangetimepicker';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_DATERANGEPICKER],
            [self::class, self::MODULE_FORMINPUT_DATERANGETIMEPICKER],
        );
    }

    public function useTime(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_DATERANGETIMEPICKER:
                return true;
        }

        return parent::useTime($componentVariation);
    }

    public function getLabelText(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_DATERANGEPICKER:
                return TranslationAPIFacade::getInstance()->__('Dates', 'pop-coreprocessors');

            case self::MODULE_FORMINPUT_DATERANGETIMEPICKER:
                return TranslationAPIFacade::getInstance()->__('Date/Time', 'pop-coreprocessors');
        }
        
        return parent::getLabelText($componentVariation, $props);
    }

    public function isMandatory(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_DATERANGETIMEPICKER:
                return true;
        }
        
        return parent::isMandatory($componentVariation, $props);
    }
}



