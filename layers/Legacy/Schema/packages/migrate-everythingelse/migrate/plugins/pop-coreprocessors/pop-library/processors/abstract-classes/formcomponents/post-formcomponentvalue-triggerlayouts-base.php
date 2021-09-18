<?php

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;
use PoPSchema\CustomPosts\TypeResolvers\UnionType\CustomPostUnionTypeResolver;

abstract class PoP_Module_Processor_PostTriggerLayoutFormComponentValuesBase extends PoP_Module_Processor_TriggerLayoutFormComponentValuesBase
{
    public function getTriggerRelationalTypeResolver(array $module): ?RelationalTypeResolverInterface
    {
        return CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetObjectTypeResolver();
    }
}
