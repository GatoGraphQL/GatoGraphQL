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

    public function useTime(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_DATERANGETIMEPICKER:
                return true;
        }

        return parent::useTime($module);
    }

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_DATERANGEPICKER:
                return TranslationAPIFacade::getInstance()->__('Dates', 'pop-coreprocessors');

            case self::MODULE_FORMINPUT_DATERANGETIMEPICKER:
                return TranslationAPIFacade::getInstance()->__('Date/Time', 'pop-coreprocessors');
        }
        
        return parent::getLabelText($module, $props);
    }

    public function isMandatory(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_DATERANGETIMEPICKER:
                return true;
        }
        
        return parent::isMandatory($module, $props);
    }
}



