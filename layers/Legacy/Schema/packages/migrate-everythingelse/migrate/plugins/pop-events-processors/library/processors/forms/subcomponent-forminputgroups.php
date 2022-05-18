<?php

class PoP_Events_Module_Processor_SubcomponentFormInputGroups extends PoP_Module_Processor_SubcomponentFormComponentGroupsBase
{
    public final const MODULE_FILTERINPUTGROUP_EVENTSCOPE = 'filterinputgroup-eventscope';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FILTERINPUTGROUP_EVENTSCOPE],
        );
    }

    public function getLabelClass(array $component)
    {
        $ret = parent::getLabelClass($component);

        switch ($component[1]) {
            case self::COMPONENT_FILTERINPUTGROUP_EVENTSCOPE:
                $ret .= ' col-sm-2';
                break;
        }

        return $ret;
    }
    public function getFormcontrolClass(array $component)
    {
        $ret = parent::getFormcontrolClass($component);

        switch ($component[1]) {
            case self::COMPONENT_FILTERINPUTGROUP_EVENTSCOPE:
                $ret .= ' col-sm-10';
                break;
        }

        return $ret;
    }

    public function getComponentSubname(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_FILTERINPUTGROUP_EVENTSCOPE:
                return 'readable';
        }

        return parent::getComponentSubname($component);
    }

    public function getComponentSubmodule(array $component)
    {
        $components = array(
            self::COMPONENT_FILTERINPUTGROUP_EVENTSCOPE => [PoP_Events_Module_Processor_DateRangeComponentFilterInputs::class, PoP_Events_Module_Processor_DateRangeComponentFilterInputs::COMPONENT_FILTERINPUT_EVENTSCOPE],
        );

        if ($component = $components[$component[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubmodule($component);
    }
}



