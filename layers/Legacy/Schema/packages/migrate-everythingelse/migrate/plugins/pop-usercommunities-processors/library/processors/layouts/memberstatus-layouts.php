<?php

class GD_URE_Module_Processor_MemberStatusLayouts extends GD_URE_Module_Processor_MemberStatusLayoutsBase
{
    public final const COMPONENT_URE_LAYOUTUSER_MEMBERSTATUS = 'ure-layoutuser-memberstatus-nodesc';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_URE_LAYOUTUSER_MEMBERSTATUS,
        );
    }
}


