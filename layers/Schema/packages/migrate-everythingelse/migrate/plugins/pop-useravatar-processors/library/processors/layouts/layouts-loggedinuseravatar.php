<?php

class PoP_Module_Processor_LoggedInUserAvatars extends PoP_Module_Processor_LoggedInUserAvatarsBase
{
    public const MODULE_LAYOUT_LOGGEDINUSERAVATAR = 'layout-loggedinuseravatar';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_LOGGEDINUSERAVATAR],
        );
    }
}



