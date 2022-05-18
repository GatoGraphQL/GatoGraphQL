<?php

abstract class GD_URE_Module_Processor_UserCommunityLayoutsBase extends PoP_Module_Processor_SubcomponentLayoutsBase
{
    public function getSubcomponentField(array $componentVariation)
    {
        return 'activeCommunities';
    }
}
