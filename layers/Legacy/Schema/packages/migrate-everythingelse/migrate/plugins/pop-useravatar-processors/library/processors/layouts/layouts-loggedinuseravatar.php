<?php

class PoP_Module_Processor_LoggedInUserAvatars extends PoP_Module_Processor_LoggedInUserAvatarsBase
{
    public final const COMPONENT_LAYOUT_LOGGEDINUSERAVATAR = 'layout-loggedinuseravatar';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_LOGGEDINUSERAVATAR,
        );
    }
}



