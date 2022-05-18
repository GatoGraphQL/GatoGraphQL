<?php

class PoP_Module_Processor_LoggedInUserAvatars extends PoP_Module_Processor_LoggedInUserAvatarsBase
{
    public final const COMPONENT_LAYOUT_LOGGEDINUSERAVATAR = 'layout-loggedinuseravatar';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_LOGGEDINUSERAVATAR],
        );
    }
}



