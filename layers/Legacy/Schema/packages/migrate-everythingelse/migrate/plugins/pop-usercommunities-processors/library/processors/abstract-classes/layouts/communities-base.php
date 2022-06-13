<?php

abstract class GD_URE_Module_Processor_UserCommunityLayoutsBase extends PoP_Module_Processor_SubcomponentLayoutsBase
{
    public function getSubcomponentFieldNode(\PoP\ComponentModel\Component\Component $component)
    {
        return 'activeCommunities';
    }
}
