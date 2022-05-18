<?php

class PoP_Events_Module_Processor_SubcomponentFormInputGroups extends PoP_Module_Processor_SubcomponentFormComponentGroupsBase
{
    public final const MODULE_FILTERINPUTGROUP_EVENTSCOPE = 'filterinputgroup-eventscope';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUTGROUP_EVENTSCOPE],
        );
    }

    public function getLabelClass(array $componentVariation)
    {
        $ret = parent::getLabelClass($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_FILTERINPUTGROUP_EVENTSCOPE:
                $ret .= ' col-sm-2';
                break;
        }

        return $ret;
    }
    public function getFormcontrolClass(array $componentVariation)
    {
        $ret = parent::getFormcontrolClass($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_FILTERINPUTGROUP_EVENTSCOPE:
                $ret .= ' col-sm-10';
                break;
        }

        return $ret;
    }

    public function getComponentSubname(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FILTERINPUTGROUP_EVENTSCOPE:
                return 'readable';
        }

        return parent::getComponentSubname($componentVariation);
    }

    public function getComponentSubmodule(array $componentVariation)
    {
        $components = array(
            self::MODULE_FILTERINPUTGROUP_EVENTSCOPE => [PoP_Events_Module_Processor_DateRangeComponentFilterInputs::class, PoP_Events_Module_Processor_DateRangeComponentFilterInputs::MODULE_FILTERINPUT_EVENTSCOPE],
        );

        if ($component = $components[$componentVariation[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubmodule($componentVariation);
    }
}



