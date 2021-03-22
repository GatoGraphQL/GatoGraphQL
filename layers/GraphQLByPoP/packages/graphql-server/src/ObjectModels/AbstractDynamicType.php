<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use GraphQLByPoP\GraphQLServer\ObjectModels\AbstractType;

abstract class AbstractDynamicType extends AbstractType
{
    public function isDynamicType(): bool
    {
        return true;
    }

    /**
     * Change the name for the dynamic type, since it comes from a different
     * field in the schema or, if not provided, it is dynamically generated.
     * This name is either namespaced or not (depending on configuration),
     * it doesn't support the two different cases
     */
    public function getNamespacedName(): string
    {
        return $this->getName();
    }

    /**
     * Change the name for the dynamic type, since it comes from a different
     * field in the schema or, if not provided, it is dynamically generated
     * This name is either namespaced or not (depending on configuration),
     * it doesn't support the two different cases
     */
    public function getElementName(): string
    {
        return $this->getName();
    }

    /**
     * Dynamic types (Enum and InputObject) can't retrieve their name
     * from the usual property in the schema, since that belongs to the
     * field name, not the type name
     *
     * Their names must be provided through some custom property
     *
     * If not provided, the name is generated dynamically,
     * composed by their field and their kind.  And too make sure
     * their names are unique, also their full path is included.
     * Otherwise, field of type "enum" with name "status" but under
     * types "User" and "Post" would have the same name and collide
     */
    public function getName(): string
    {
        // If they have provided a name, use it
        if ($dynamicName = $this->schemaDefinition[$this->getDynamicTypeNamePropertyInSchema()] ?? null) {
            return $dynamicName;
        }
        // Otherwise, generate a unique name
        return implode(
            '__', // Can't use '_', because it's reserved for the type/interface namespaces instead
            array_map(
                'ucfirst',
                $this->schemaDefinitionPath
            )
        );
    }

    /**
     * Indicate under what property in the schema definition
     * is the Dynamic Type's name provided
     */
    abstract protected function getDynamicTypeNamePropertyInSchema(): string;
}
