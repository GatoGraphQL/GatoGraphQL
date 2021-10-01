<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleProcessors;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;

interface DataloadQueryArgsFilterInputModuleProcessorInterface extends FormComponentModuleProcessorInterface
{
    public function getValue(array $module, ?array $source = null): mixed;
    public function isInputSetInSource(array $module, ?array $source = null): mixed;
    public function getFilterInput(array $module): ?array;
    public function getSchemaFilterInputTypeResolver(array $module): InputTypeResolverInterface;
    public function getSchemaFilterInputDescription(array $module): ?string;
    public function getSchemaFilterInputDefaultValue(array $module): mixed;
    public function getSchemaFilterInputTypeModifiers(array $module): ?int;
}
