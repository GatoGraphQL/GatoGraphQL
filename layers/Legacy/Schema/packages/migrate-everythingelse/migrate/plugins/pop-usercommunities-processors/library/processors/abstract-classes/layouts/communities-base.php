<?php
use PoPSchema\Users\TypeResolvers\UserTypeResolver;

abstract class GD_URE_Module_Processor_UserCommunityLayoutsBase extends PoP_Module_Processor_SubcomponentLayoutsBase
{
    public function getSubcomponentField(array $module)
    {
        return 'activeCommunities';
    }
}
