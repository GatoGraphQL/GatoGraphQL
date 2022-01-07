<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Versioning;

interface VersioningServiceInterface
{
    /**
     * Indicates the version constraints for specific fields in the schema
     */
    public static function getVersionConstraintsForField(string $maybeNamespacedTypeName, string $fieldName): ?string;

    /**
     * Indicates the version constraints for specific directives in the schema
     */
    public static function getVersionConstraintsForDirective(string $directiveName): ?string;
}
