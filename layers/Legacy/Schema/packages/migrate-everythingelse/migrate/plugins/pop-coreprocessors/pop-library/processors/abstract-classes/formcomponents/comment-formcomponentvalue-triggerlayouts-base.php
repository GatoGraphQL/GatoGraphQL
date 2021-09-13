<?php
use PoPSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolver;

abstract class PoP_Module_Processor_CommentTriggerLayoutFormComponentValuesBase extends PoP_Module_Processor_TriggerLayoutFormComponentValuesBase
{
    public function getTriggerTypeResolverClass(array $module): ?string
    {
        return CommentObjectTypeResolver::class;
    }
}
