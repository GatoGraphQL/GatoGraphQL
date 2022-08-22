<?php

class GD_EM_Module_Processor_DateTimeLayouts extends GD_EM_Module_Processor_DateTimeLayoutsBase
{
    public final const COMPONENT_EM_LAYOUT_DATETIME = 'em-layout-datetime';
    public final const COMPONENT_EM_LAYOUT_DATETIMEHORIZONTAL = 'em-layout-datetimehorizontal';
    public final const COMPONENT_EM_LAYOUT_DATETIMEDOWNLOADLINKS = 'em-layout-datetimedownloadlinks';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_EM_LAYOUT_DATETIME,
            self::COMPONENT_EM_LAYOUT_DATETIMEHORIZONTAL,
            self::COMPONENT_EM_LAYOUT_DATETIMEDOWNLOADLINKS,
        );
    }
    public function getSeparator(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_EM_LAYOUT_DATETIMEHORIZONTAL:
                return '&nbsp;';
        }

        return parent::getSeparator($component, $props);
    }

    public function addDownloadlinks(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_EM_LAYOUT_DATETIMEDOWNLOADLINKS:
                return true;
        }

        return parent::addDownloadlinks($component);
    }
}



