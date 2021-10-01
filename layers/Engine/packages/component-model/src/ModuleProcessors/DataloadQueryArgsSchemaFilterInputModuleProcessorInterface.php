<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleProcessors;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;

interface DataloadQueryArgsSchemaFilterInputModuleProcessorInterface
{
    public function getSchemaFilterInputTypeResolver(array $module): InputTypeResolverInterface;
    public function getSchemaFilterInputDescription(array $module): ?string;
    public function getSchemaFilterInputDeprecationDescription(array $module): ?string;
    public function getSchemaFilterInputDefaultValue(array $module): mixed;
    public function getSchemaFilterInputTypeModifiers(array $module): int;
}
