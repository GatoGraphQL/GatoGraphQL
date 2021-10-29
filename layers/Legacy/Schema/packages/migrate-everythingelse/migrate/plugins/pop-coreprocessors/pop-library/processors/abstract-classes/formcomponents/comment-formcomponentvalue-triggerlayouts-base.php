<?php
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolver;

abstract class PoP_Module_Processor_CommentTriggerLayoutFormComponentValuesBase extends PoP_Module_Processor_TriggerLayoutFormComponentValuesBase
{
    public function getTriggerRelationalTypeResolver(array $module): ?RelationalTypeResolverInterface
    {
        return $this->instanceManager->getInstance(CommentObjectTypeResolver::class);
    }
}
