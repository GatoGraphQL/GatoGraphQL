<?php

class GD_URE_Module_Processor_MemberStatusLayouts extends GD_URE_Module_Processor_MemberStatusLayoutsBase
{
    public final const MODULE_URE_LAYOUTUSER_MEMBERSTATUS = 'ure-layoutuser-memberstatus-nodesc';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_URE_LAYOUTUSER_MEMBERSTATUS],
        );
    }
}


