<?php

class PoP_Module_Processor_LoggedInUserAvatars extends PoP_Module_Processor_LoggedInUserAvatarsBase
{
    public final const MODULE_LAYOUT_LOGGEDINUSERAVATAR = 'layout-loggedinuseravatar';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_LOGGEDINUSERAVATAR],
        );
    }
}



