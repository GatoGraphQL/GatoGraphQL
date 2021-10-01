<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleProcessors;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;

interface DataloadQueryArgsSchemaFilterInputModuleProcessorInterface
{
    public function getSchemaFilterInputTypeResolver(array $module): InputTypeResolverInterface;
    public function getSchemaFilterInputDescription(array $module): ?string;
    public function getSchemaFilterInputDeprecationDescription(array $module): ?string;
    public function getSchemaFilterInputIsArrayType(array $module): bool;
    public function getSchemaFilterInputIsNonNullableItemsInArrayType(array $module): bool;
    public function getSchemaFilterInputIsArrayOfArraysType(array $module): bool;
    public function getSchemaFilterInputIsNonNullableItemsInArrayOfArraysType(array $module): bool;
    public function getSchemaFilterInputMandatory(array $module): bool;
    public function getSchemaFilterInputDefaultValue(array $module): mixed;
    public function addSchemaDefinitionForFilter(array &$schemaDefinition, array $module): void;
}
