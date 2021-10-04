<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleProcessors;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;

interface DataloadQueryArgsFilterInputModuleProcessorInterface extends FormComponentModuleProcessorInterface
{
    public function getValue(array $module, ?array $source = null): mixed;
    public function isInputSetInSource(array $module, ?array $source = null): mixed;
    public function getFilterInput(array $module): ?array;
    public function getFilterInputTypeResolver(array $module): InputTypeResolverInterface;
    public function getFilterInputDescription(array $module): ?string;
    public function getFilterInputDefaultValue(array $module): mixed;
    public function getFilterInputTypeModifiers(array $module): int;
}
