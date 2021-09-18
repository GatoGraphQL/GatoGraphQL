<?php
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;
use PoPSchema\CustomPosts\TypeResolvers\UnionType\CustomPostUnionTypeResolver;

abstract class PoP_Module_Processor_PostSelectableTypeaheadFormComponentsBase extends PoP_Module_Processor_SelectableTypeaheadFormComponentsBase
{
    public function getTriggerRelationalTypeResolver(array $module): ?RelationalTypeResolverInterface
    {
        return CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetObjectTypeResolver();
    }
}
