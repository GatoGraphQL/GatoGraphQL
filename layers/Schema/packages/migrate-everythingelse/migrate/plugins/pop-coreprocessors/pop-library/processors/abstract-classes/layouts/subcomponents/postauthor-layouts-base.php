<?php
use PoPSchema\Users\TypeResolvers\UserTypeResolver;

abstract class PoP_Module_Processor_PostAuthorLayoutsBase extends PoP_Module_Processor_SubcomponentLayoutsBase
{
    public function getSubcomponentField(array $module)
    {
        return 'authors';
    }
}
