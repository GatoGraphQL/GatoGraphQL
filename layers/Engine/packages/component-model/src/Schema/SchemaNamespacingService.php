<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

use PoP\Root\Helpers\ClassHelpers;

class SchemaNamespacingService implements SchemaNamespacingServiceInterface
{
    /**
     * @var array<string,string>
     */
    protected array $classOwnerAndProjectNamespaceSchemaNamespaces = [];

    public function addSchemaNamespaceForClassOwnerAndProjectNamespace(string $classOwnerAndProjectNamespace, $schemaNamespace): void
    {
        $this->classOwnerAndProjectNamespaceSchemaNamespaces[$classOwnerAndProjectNamespace] = $schemaNamespace;
    }

    public function getSchemaNamespace(string $class): string
    {
        $classOwnerAndProjectNamespace = ClassHelpers::getClassPSR4Namespace($class);
        // Check if an entry for this combination of Owner + class has been provided
        if (isset($this->classOwnerAndProjectNamespaceSchemaNamespaces[$classOwnerAndProjectNamespace])) {
            return $this->classOwnerAndProjectNamespaceSchemaNamespaces[$classOwnerAndProjectNamespace];
        }
        return $this->convertClassNamespaceToSchemaNamespace($classOwnerAndProjectNamespace);
    }

    protected function convertClassNamespaceToSchemaNamespace(string $classNamespace): string
    {
        return str_replace('\\', SchemaDefinitionTokens::NAMESPACE_SEPARATOR, $classNamespace);
    }

    public function getSchemaNamespacedName(string $schemaNamespace, string $name): string
    {
        return ($schemaNamespace ? $schemaNamespace . SchemaDefinitionTokens::NAMESPACE_SEPARATOR : '') . $name;
    }
}
