<?php

class GD_URE_Module_Processor_MemberPrivilegesLayouts extends GD_URE_Module_Processor_MemberPrivilegesLayoutsBase
{
    public final const COMPONENT_URE_LAYOUTUSER_MEMBERPRIVILEGES = 'ure-layoutuser-memberprivileges-nodesc';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_URE_LAYOUTUSER_MEMBERPRIVILEGES,
        );
    }
}


