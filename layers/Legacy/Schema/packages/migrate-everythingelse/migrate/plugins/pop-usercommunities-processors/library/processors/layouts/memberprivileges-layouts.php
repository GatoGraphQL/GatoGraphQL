<?php

class GD_URE_Module_Processor_MemberPrivilegesLayouts extends GD_URE_Module_Processor_MemberPrivilegesLayoutsBase
{
    public final const MODULE_URE_LAYOUTUSER_MEMBERPRIVILEGES = 'ure-layoutuser-memberprivileges-nodesc';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_URE_LAYOUTUSER_MEMBERPRIVILEGES],
        );
    }
}


