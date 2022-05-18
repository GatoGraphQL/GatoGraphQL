<?php
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

abstract class PoP_Module_Processor_UserTriggerLayoutFormComponentValuesBase extends PoP_Module_Processor_TriggerLayoutFormComponentValuesBase
{
    public function getTriggerRelationalTypeResolver(array $component): ?RelationalTypeResolverInterface
    {
        return $this->instanceManager->getInstance(UserObjectTypeResolver::class);
    }
}
