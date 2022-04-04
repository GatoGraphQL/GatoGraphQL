<?php

class PoP_Module_Processor_UserQuickLinkLayouts extends PoP_Module_Processor_UserQuickLinkLayoutsBase
{
    public final const MODULE_LAYOUTUSER_QUICKLINKS = 'layoutuser-quicklinks';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUTUSER_QUICKLINKS],
        );
    }
}



