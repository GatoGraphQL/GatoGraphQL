<?php

class GD_EM_Module_Processor_DateTimeLayouts extends GD_EM_Module_Processor_DateTimeLayoutsBase
{
    public final const MODULE_EM_LAYOUT_DATETIME = 'em-layout-datetime';
    public final const MODULE_EM_LAYOUT_DATETIMEHORIZONTAL = 'em-layout-datetimehorizontal';
    public final const MODULE_EM_LAYOUT_DATETIMEDOWNLOADLINKS = 'em-layout-datetimedownloadlinks';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_EM_LAYOUT_DATETIME],
            [self::class, self::MODULE_EM_LAYOUT_DATETIMEHORIZONTAL],
            [self::class, self::MODULE_EM_LAYOUT_DATETIMEDOWNLOADLINKS],
        );
    }
    public function getSeparator(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_EM_LAYOUT_DATETIMEHORIZONTAL:
                return '&nbsp;';
        }

        return parent::getSeparator($module, $props);
    }

    public function addDownloadlinks(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_EM_LAYOUT_DATETIMEDOWNLOADLINKS:
                return true;
        }

        return parent::addDownloadlinks($module);
    }
}



