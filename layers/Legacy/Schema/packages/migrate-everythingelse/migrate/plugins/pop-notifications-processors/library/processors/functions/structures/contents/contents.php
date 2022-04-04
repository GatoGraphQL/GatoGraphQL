<?php

class GD_AAL_Module_Processor_FunctionsContents extends PoP_Module_Processor_ContentsBase
{
    public final const MODULE_CONTENT_MARKNOTIFICATIONASREAD = 'content-marknotificationasread';
    public final const MODULE_CONTENT_MARKNOTIFICATIONASUNREAD = 'content-marknotificationasunread';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CONTENT_MARKNOTIFICATIONASREAD],
            [self::class, self::MODULE_CONTENT_MARKNOTIFICATIONASUNREAD],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_CONTENT_MARKNOTIFICATIONASREAD => [GD_AAL_Module_Processor_FunctionsContentMultipleInners::class, GD_AAL_Module_Processor_FunctionsContentMultipleInners::MODULE_CONTENTINNER_MARKNOTIFICATIONASREAD],
            self::MODULE_CONTENT_MARKNOTIFICATIONASUNREAD => [GD_AAL_Module_Processor_FunctionsContentMultipleInners::class, GD_AAL_Module_Processor_FunctionsContentMultipleInners::MODULE_CONTENTINNER_MARKNOTIFICATIONASUNREAD],
        );
        if ($inner = $inners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }
}


