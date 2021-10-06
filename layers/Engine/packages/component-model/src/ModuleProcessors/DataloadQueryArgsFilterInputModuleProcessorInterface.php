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
    /**
     * Watch out! The GraphQL spec does not include deprecations for arguments,
     * only for fields and enum values, but here it is added nevertheless.
     * This message is shown on runtime when executing a query with a deprecated field,
     * but it's not shown when doing introspection.
     *
     * @see https://spec.graphql.org/draft/#sec-Schema-Introspection.Schema-Introspection-Schema
     */
    public function getFilterInputDeprecationDescription(array $module): ?string;
    public function getFilterInputDefaultValue(array $module): mixed;
    public function getFilterInputTypeModifiers(array $module): int;
}
