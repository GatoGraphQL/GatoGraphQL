<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers\ObjectType;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\ComponentModel\Schema\SchemaDefinitionServiceInterface;
use PoP\ComponentModel\Engine\EngineInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractGlobalObjectTypeFieldResolver;
use PoP\ComponentModel\HelperServices\SemverHelperServiceInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\CMS\CMSServiceInterface;
use PoP\Engine\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Translation\TranslationAPIInterface;

class CoreGlobalObjectTypeFieldResolver extends AbstractGlobalObjectTypeFieldResolver
{
    protected StringScalarTypeResolver $stringScalarTypeResolver;
    protected BooleanScalarTypeResolver $booleanScalarTypeResolver;

    #[Required]
    public function autowireCoreGlobalObjectTypeFieldResolver(
        StringScalarTypeResolver $stringScalarTypeResolver,
        BooleanScalarTypeResolver $booleanScalarTypeResolver,
    ): void {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
    }

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

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        $types = [
            'typeName' => $this->stringScalarTypeResolver,
            'namespace' => $this->stringScalarTypeResolver,
            'qualifiedTypeName' => $this->stringScalarTypeResolver,
            'isType' => $this->booleanScalarTypeResolver,
            'implements' => $this->booleanScalarTypeResolver,
        ];
        return $types[$fieldName] ?? parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?int
    {
        switch ($fieldName) {
            case 'typeName':
            case 'namespace':
            case 'qualifiedTypeName':
            case 'isType':
            case 'implements':
                return SchemaTypeModifiers::NON_NULLABLE;
        }
        return parent::getSchemaFieldTypeModifiers($objectTypeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'typeName' => $this->translationAPI->__('The object\'s type', 'pop-component-model'),
            'namespace' => $this->translationAPI->__('The object\'s namespace', 'pop-component-model'),
            'qualifiedTypeName' => $this->translationAPI->__('The object\'s namespace + type', 'pop-component-model'),
            'isType' => $this->translationAPI->__('Indicate if the object is of a given type', 'pop-component-model'),
            'implements' => $this->translationAPI->__('Indicate if the object implements a given interface', 'pop-component-model'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($objectTypeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($objectTypeResolver, $fieldName);
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
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        switch ($fieldName) {
            case 'typeName':
                return $objectTypeResolver->getTypeName();
            case 'namespace':
                return $objectTypeResolver->getNamespace();
            case 'qualifiedTypeName':
                return $objectTypeResolver->getNamespacedTypeName();
            case 'isType':
                $typeName = $fieldArgs['type'];
                // If the provided typeName contains the namespace separator, then compare by qualifiedType
                if (str_contains($typeName, SchemaDefinition::TOKEN_NAMESPACE_SEPARATOR)) {
                    /**
                     * @todo Replace the code below with:
                     *
                     *     return $typeName == $objectTypeResolver->getNamespacedTypeName();
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
                        $typeName == $objectTypeResolver->getNamespacedTypeName()
                        || $typeName == $objectTypeResolver->getTypeName();
                }
                return $typeName == $objectTypeResolver->getTypeName();
            case 'implements':
                $interface = $fieldArgs['interface'];
                $implementedInterfaceTypeResolvers = $objectTypeResolver->getAllImplementedInterfaceTypeResolvers();
                // If the provided interface contains the namespace separator, then compare by qualifiedInterface
                $useNamespaced = str_contains($interface, SchemaDefinition::TOKEN_NAMESPACE_SEPARATOR);
                $implementedInterfaceNames = array_map(
                    function (InterfaceTypeResolverInterface $interfaceTypeResolver) use ($useNamespaced) {
                        if ($useNamespaced) {
                            return $interfaceTypeResolver->getNamespacedTypeName();
                        }
                        return $interfaceTypeResolver->getTypeName();
                    },
                    $implementedInterfaceTypeResolvers
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
                            function (InterfaceTypeResolverInterface $interfaceTypeResolver) {
                                return $interfaceTypeResolver->getTypeName();
                            },
                            $implementedInterfaceTypeResolvers
                        )
                    );
                }
                /**
                 * -- End code --
                 */
                return in_array($interface, $implementedInterfaceNames);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
