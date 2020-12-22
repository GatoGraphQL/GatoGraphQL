<?php
use PoPSchema\Users\TypeResolvers\UserTypeResolver;

abstract class PoP_Module_Processor_UserTriggerLayoutFormComponentValuesBase extends PoP_Module_Processor_TriggerLayoutFormComponentValuesBase
{
    public function getTriggerTypeResolverClass(array $module): ?string
    {
        return UserTypeResolver::class;
    }
}
