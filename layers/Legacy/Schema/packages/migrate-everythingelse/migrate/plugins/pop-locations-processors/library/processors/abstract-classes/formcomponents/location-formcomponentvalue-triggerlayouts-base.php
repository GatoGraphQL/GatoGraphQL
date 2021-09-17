<?php
use PoPSchema\Locations\TypeResolvers\ObjectType\LocationObjectTypeResolver;

abstract class PoP_Module_Processor_LocationTriggerLayoutFormComponentValuesBase extends PoP_Module_Processor_TriggerLayoutFormComponentValuesBase
{
    public function getTriggerTypeResolverClass(array $module): ?string
    {
        return $this->instanceManager->getInstance(LocationObjectTypeResolver::class);
    }
}
