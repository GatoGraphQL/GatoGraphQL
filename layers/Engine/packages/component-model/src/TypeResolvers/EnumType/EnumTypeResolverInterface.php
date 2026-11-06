<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\EnumType;

use BackedEnum;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\DeprecatableInputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\LeafOutputTypeResolverInterface;

interface EnumTypeResolverInterface extends ConcreteTypeResolverInterface, DeprecatableInputTypeResolverInterface, LeafOutputTypeResolverInterface
{
    /**
     * Opt-in: the string-backed native PHP enum whose cases are the
     * values of this GraphQL enum.
     *
     * When provided, the input value is coerced into the enum case
     * (so field/directive arguments and input fields are read as the
     * enum, never as a string), the enum case is serialized into its
     * value when printed in the response, and the enum values default
     * to the enum's cases.
     *
     * The PHP enum is the closed set of values, so the enum values
     * must not be extended via hooks.
     *
     * @return class-string<BackedEnum>|null
     */
    public function getBackedEnumClass(): ?string;
    /**
     * The values in the enum
     *
     * @return string[]
     */
    public function getEnumValues(): array;
    /**
     * The “sensitive” values in the enum
     *
     * @return string[]
     */
    public function getSensitiveEnumValues(): array;
    /**
     * Description for a specific enum value
     */
    public function getEnumValueDescription(string $enumValue): ?string;
    /**
     * Deprecation message for a specific enum value
     */
    public function getEnumValueDeprecationMessage(string $enumValue): ?string;
    /**
     * Consolidation of the enum values. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     *
     * @return string[]
     */
    public function getConsolidatedEnumValues(): array;
    /**
     * @return string[]
     */
    public function getConsolidatedAdminEnumValues(): array;
    public function getConsolidatedEnumValueDescription(string $enumValue): ?string;
    public function getConsolidatedEnumValueDeprecationMessage(string $enumValue): ?string;
    /**
     * @return array<string,mixed>
     */
    public function getEnumValueSchemaDefinition(string $enumValue): array;
    /**
     * @return array<string,array<string,mixed>>
     */
    public function getEnumSchemaDefinition(): array;
}
