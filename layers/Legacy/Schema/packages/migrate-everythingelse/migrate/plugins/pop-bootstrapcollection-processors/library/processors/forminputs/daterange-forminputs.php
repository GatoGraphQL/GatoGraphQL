<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_DateRangeComponentInputs extends PoP_Module_Processor_DateRangeFormInputsBase
{
    public final const COMPONENT_FORMINPUT_DATERANGEPICKER = 'forminput-daterangepicker';
    public final const COMPONENT_FORMINPUT_DATERANGETIMEPICKER = 'forminput-daterangetimepicker';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMINPUT_DATERANGEPICKER,
            self::COMPONENT_FORMINPUT_DATERANGETIMEPICKER,
        );
    }

    public function useTime(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_DATERANGETIMEPICKER:
                return true;
        }

        return parent::useTime($component);
    }

    public function getLabelText(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_DATERANGEPICKER:
                return TranslationAPIFacade::getInstance()->__('Dates', 'pop-coreprocessors');

            case self::COMPONENT_FORMINPUT_DATERANGETIMEPICKER:
                return TranslationAPIFacade::getInstance()->__('Date/Time', 'pop-coreprocessors');
        }
        
        return parent::getLabelText($component, $props);
    }

    public function isMandatory(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_DATERANGETIMEPICKER:
                return true;
        }
        
        return parent::isMandatory($component, $props);
    }
}



