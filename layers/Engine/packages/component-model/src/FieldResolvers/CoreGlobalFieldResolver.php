<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractGlobalFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

class CoreGlobalFieldResolver extends AbstractGlobalFieldResolver
{
    public function getFieldNamesToResolve(): array
    {
        return [
            'typeName',
            'namespace',
            'qualifiedTypeName',
            'isType',
            'implements',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'typeName' => SchemaDefinition::TYPE_STRING,
            'namespace' => SchemaDefinition::TYPE_STRING,
            'qualifiedTypeName' => SchemaDefinition::TYPE_STRING,
            'isType' => SchemaDefinition::TYPE_BOOL,
            'implements' => SchemaDefinition::TYPE_BOOL,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(TypeResolverInterface $typeResolver, string $fieldName): ?int
    {
        switch ($fieldName) {
            case 'typeName':
            case 'namespace':
            case 'qualifiedTypeName':
            case 'isType':
            case 'implements':
                return SchemaTypeModifiers::NON_NULLABLE;
        }
        return parent::getSchemaFieldTypeModifiers($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'typeName' => $this->translationAPI->__('The object\'s type', 'pop-component-model'),
            'namespace' => $this->translationAPI->__('The object\'s namespace', 'pop-component-model'),
            'qualifiedTypeName' => $this->translationAPI->__('The object\'s namespace + type', 'pop-component-model'),
            'isType' => $this->translationAPI->__('Indicate if the object is of a given type', 'pop-component-model'),
            'implements' => $this->translationAPI->__('Indicate if the object implements a given interface', 'pop-component-model'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($typeResolver, $fieldName);
        switch ($fieldName) {
            case 'isType':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'type',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('The type name to compare against', 'component-model'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                    ]
                );

            case 'implements':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'interface',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('The interface name to compare against', 'component-model'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                    ]
                );
        }

        return $schemaFieldArgs;
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        switch ($fieldName) {
            case 'typeName':
                return $typeResolver->getTypeName();
            case 'namespace':
                return $typeResolver->getNamespace();
            case 'qualifiedTypeName':
                return $typeResolver->getNamespacedTypeName();
            case 'isType':
                $typeName = $fieldArgs['type'];
                // If the provided typeName contains the namespace separator, then compare by qualifiedType
                if (str_contains($typeName, SchemaDefinition::TOKEN_NAMESPACE_SEPARATOR)) {
                    /**
                     * @todo Replace the code below with:
                     *
                     *     return $typeName == $typeResolver->getNamespacedTypeName();
                     *
                     * Currently, because the GraphQL spec doesn't support namespaces,
                     * we are using "_" as the namespace separator, instead of "/".
                     * But this character can also be part of the Type name!
                     * So only temporarily, compare both the namespaced and the
                     * normal type name, until can use "/".
                     *
                     * @see https://github.com/graphql/graphql-spec/issues/163
                     */
                    return
                        $typeName == $typeResolver->getNamespacedTypeName()
                        || $typeName == $typeResolver->getTypeName();
                }
                return $typeName == $typeResolver->getTypeName();
            case 'implements':
                $interface = $fieldArgs['interface'];
                $implementedInterfaceResolverInstances = $typeResolver->getAllImplementedInterfaceResolverInstances();
                // If the provided interface contains the namespace separator, then compare by qualifiedInterface
                $useNamespaced = str_contains($interface, SchemaDefinition::TOKEN_NAMESPACE_SEPARATOR);
                $implementedInterfaceNames = array_map(
                    function ($interfaceResolver) use ($useNamespaced) {
                        if ($useNamespaced) {
                            return $interfaceResolver->getNamespacedInterfaceName();
                        }
                        return $interfaceResolver->getInterfaceName();
                    },
                    $implementedInterfaceResolverInstances
                );
                /**
                 * @todo Remove the block of code below.
                 *
                 * Currently, because the GraphQL spec doesn't support namespaces,
                 * we are using "_" as the namespace separator, instead of "/".
                 * But this character can also be part of the Interface name!
                 * So only temporarily, also add the interface names to the
                 * array to compare, until can use "/".
                 *
                 * @see https://github.com/graphql/graphql-spec/issues/163
                 *
                 * -- Begin code --
                 */
                if ($useNamespaced) {
                    $implementedInterfaceNames = array_merge(
                        $implementedInterfaceNames,
                        array_map(
                            function ($interfaceResolver) {
                                return $interfaceResolver->getInterfaceName();
                            },
                            $implementedInterfaceResolverInstances
                        )
                    );
                }
                /**
                 * -- End code --
                 */
                return in_array($interface, $implementedInterfaceNames);
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
