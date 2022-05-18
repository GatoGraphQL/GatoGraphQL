<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_DateRangeComponentInputs extends PoP_Module_Processor_DateRangeFormInputsBase
{
    public final const MODULE_FORMINPUT_DATERANGEPICKER = 'forminput-daterangepicker';
    public final const MODULE_FORMINPUT_DATERANGETIMEPICKER = 'forminput-daterangetimepicker';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINPUT_DATERANGEPICKER],
            [self::class, self::COMPONENT_FORMINPUT_DATERANGETIMEPICKER],
        );
    }

    public function useTime(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUT_DATERANGETIMEPICKER:
                return true;
        }

        return parent::useTime($component);
    }

    public function getLabelText(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUT_DATERANGEPICKER:
                return TranslationAPIFacade::getInstance()->__('Dates', 'pop-coreprocessors');

            case self::COMPONENT_FORMINPUT_DATERANGETIMEPICKER:
                return TranslationAPIFacade::getInstance()->__('Date/Time', 'pop-coreprocessors');
        }
        
        return parent::getLabelText($component, $props);
    }

    public function isMandatory(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUT_DATERANGETIMEPICKER:
                return true;
        }
        
        return parent::isMandatory($component, $props);
    }
}



