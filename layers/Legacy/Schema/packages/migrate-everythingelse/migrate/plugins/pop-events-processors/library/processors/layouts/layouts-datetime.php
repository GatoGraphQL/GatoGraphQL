<?php

class GD_EM_Module_Processor_DateTimeLayouts extends GD_EM_Module_Processor_DateTimeLayoutsBase
{
    public final const COMPONENT_EM_LAYOUT_DATETIME = 'em-layout-datetime';
    public final const COMPONENT_EM_LAYOUT_DATETIMEHORIZONTAL = 'em-layout-datetimehorizontal';
    public final const COMPONENT_EM_LAYOUT_DATETIMEDOWNLOADLINKS = 'em-layout-datetimedownloadlinks';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_EM_LAYOUT_DATETIME],
            [self::class, self::COMPONENT_EM_LAYOUT_DATETIMEHORIZONTAL],
            [self::class, self::COMPONENT_EM_LAYOUT_DATETIMEDOWNLOADLINKS],
        );
    }
    public function getSeparator(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_EM_LAYOUT_DATETIMEHORIZONTAL:
                return '&nbsp;';
        }

        return parent::getSeparator($component, $props);
    }

    public function addDownloadlinks(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_EM_LAYOUT_DATETIMEDOWNLOADLINKS:
                return true;
        }

        return parent::addDownloadlinks($component);
    }
}



