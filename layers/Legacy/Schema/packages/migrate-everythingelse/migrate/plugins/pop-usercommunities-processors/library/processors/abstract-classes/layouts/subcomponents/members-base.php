<?php

abstract class GD_URE_Module_Processor_MembersLayoutsBase extends PoP_Module_Processor_SubcomponentLayoutsBase
{
    public function getSubcomponentField(array $componentVariation)
    {
        return 'members';
    }
}
