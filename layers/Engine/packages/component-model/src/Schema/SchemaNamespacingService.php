<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

class SchemaNamespacingService implements SchemaNamespacingServiceInterface
{
    public function getSchemaNamespace(string $class): string
    {
        return $this->convertNamespace(
            $this->getOwnerAndProjectFromNamespace($class)
        );
    }

    /**
     * Following PSR-4, namespaces must contain the owner (eg: "PoP") and project name (eg: "ComponentModel")
     * Extract these 2 elements to namespace the types/interfaces
     */
    protected function getOwnerAndProjectFromNamespace(string $class): string
    {
        // First slash: between owner and project name
        $firstSlashPos = strpos($class, '\\');
        if ($firstSlashPos !== false) {
            // Second slash: between project name and everything else
            $secondSlashPos = strpos($class, '\\', $firstSlashPos + strlen('\\'));
            if ($secondSlashPos !== false) {
                // Remove everything else
                return substr($class, 0, $secondSlashPos);
            }
            // Return up to the Owner only
            return substr($class, 0, $firstSlashPos);
        }
        return '';
    }

    protected function convertNamespace(string $namespace): string
    {
        return str_replace('\\', SchemaDefinition::TOKEN_NAMESPACE_SEPARATOR, $namespace);
    }

    public function getSchemaNamespacedName(string $schemaNamespace, string $name): string
    {
        return ($schemaNamespace ? $schemaNamespace . SchemaDefinition::TOKEN_NAMESPACE_SEPARATOR : '') . $name;
    }
}
