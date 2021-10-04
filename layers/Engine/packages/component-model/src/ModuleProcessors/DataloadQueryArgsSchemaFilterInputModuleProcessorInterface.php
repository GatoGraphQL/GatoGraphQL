<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleProcessors;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;

interface DataloadQueryArgsSchemaFilterInputModuleProcessorInterface
{
    public function getFilterInputTypeResolver(array $module): InputTypeResolverInterface;
    public function getFilterInputDescription(array $module): ?string;
    public function getFilterInputDeprecationDescription(array $module): ?string;
    public function getFilterInputDefaultValue(array $module): mixed;
    public function getFilterInputTypeModifiers(array $module): int;
}
