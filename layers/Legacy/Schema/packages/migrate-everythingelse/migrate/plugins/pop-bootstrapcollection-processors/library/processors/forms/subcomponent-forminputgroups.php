<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups extends PoP_Module_Processor_SubcomponentFormComponentGroupsBase
{
    public final const MODULE_FORMINPUTGROUP_DATERANGETIMEPICKER = 'forminputgroup-daterangetimepicker';
    public final const MODULE_FILTERINPUTGROUP_POSTDATES = 'filterinputgroup-date';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUTGROUP_DATERANGETIMEPICKER],
            [self::class, self::MODULE_FILTERINPUTGROUP_POSTDATES],
        );
    }

    public function getLabelClass(array $component)
    {
        $ret = parent::getLabelClass($component);

        switch ($component[1]) {
            case self::MODULE_FILTERINPUTGROUP_POSTDATES:
                $ret .= ' col-sm-2';
                break;
        }

        return $ret;
    }
    public function getFormcontrolClass(array $component)
    {
        $ret = parent::getFormcontrolClass($component);

        switch ($component[1]) {
            case self::MODULE_FILTERINPUTGROUP_POSTDATES:
                $ret .= ' col-sm-10';
                break;
        }

        return $ret;
    }

    public function getComponentSubname(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_FORMINPUTGROUP_DATERANGETIMEPICKER:
            case self::MODULE_FILTERINPUTGROUP_POSTDATES:
                return 'readable';
        }

        return parent::getComponentSubname($component);
    }

    public function getComponentSubmodule(array $component)
    {
        $components = array(
            self::MODULE_FORMINPUTGROUP_DATERANGETIMEPICKER => [PoP_Module_Processor_DateRangeComponentInputs::class, PoP_Module_Processor_DateRangeComponentInputs::MODULE_FORMINPUT_DATERANGETIMEPICKER],
            self::MODULE_FILTERINPUTGROUP_POSTDATES => [PoP_Module_Processor_DateRangeComponentFilterInputs::class, PoP_Module_Processor_DateRangeComponentFilterInputs::MODULE_FILTERINPUT_CUSTOMPOSTDATES],
        );

        if ($component = $components[$component[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubmodule($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::MODULE_FORMINPUTGROUP_DATERANGETIMEPICKER:
                $this->setProp($this->getComponentSubmodule($component), $props, 'placeholder', TranslationAPIFacade::getInstance()->__('Click here...', 'pop-coreprocessors'));
                break;
        }

        parent::initModelProps($component, $props);
    }
}



