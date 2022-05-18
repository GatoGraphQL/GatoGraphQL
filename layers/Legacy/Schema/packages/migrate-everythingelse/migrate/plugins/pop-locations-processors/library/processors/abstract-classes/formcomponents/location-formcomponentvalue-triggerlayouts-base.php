<?php
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPCMSSchema\Locations\TypeResolvers\ObjectType\LocationObjectTypeResolver;

abstract class PoP_Module_Processor_LocationTriggerLayoutFormComponentValuesBase extends PoP_Module_Processor_TriggerLayoutFormComponentValuesBase
{
    public function getTriggerRelationalTypeResolver(array $component): ?RelationalTypeResolverInterface
    {
        return $this->instanceManager->getInstance(LocationObjectTypeResolver::class);
    }
}
