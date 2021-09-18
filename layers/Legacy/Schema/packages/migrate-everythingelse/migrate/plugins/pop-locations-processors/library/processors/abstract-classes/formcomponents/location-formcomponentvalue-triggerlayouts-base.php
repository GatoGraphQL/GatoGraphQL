<?php
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPSchema\Locations\TypeResolvers\ObjectType\LocationObjectTypeResolver;

abstract class PoP_Module_Processor_LocationTriggerLayoutFormComponentValuesBase extends PoP_Module_Processor_TriggerLayoutFormComponentValuesBase
{
    public function getTriggerRelationalTypeResolver(array $module): ?RelationalTypeResolverInterface
    {
        return $this->instanceManager->getInstance(LocationObjectTypeResolver::class);
    }
}
