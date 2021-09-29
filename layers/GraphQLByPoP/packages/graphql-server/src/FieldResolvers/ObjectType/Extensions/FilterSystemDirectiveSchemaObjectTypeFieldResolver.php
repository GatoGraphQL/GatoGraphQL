<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\FieldResolvers\ObjectType\Extensions;

use GraphQLByPoP\GraphQLServer\FieldResolvers\ObjectType\SchemaObjectTypeFieldResolver;
use GraphQLByPoP\GraphQLServer\ObjectModels\Schema;
use GraphQLByPoP\GraphQLServer\Schema\SchemaDefinitionHelpers;
use GraphQLByPoP\GraphQLServer\TypeResolvers\EnumType\DirectiveTypeEnumTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\SchemaObjectTypeResolver;
use PoP\API\Schema\SchemaDefinition;
use PoP\ComponentModel\Registries\DirectiveRegistryInterface;
use PoP\ComponentModel\Schema\SchemaHelpers;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use Symfony\Contracts\Service\Attribute\Required;

class FilterSystemDirectiveSchemaObjectTypeFieldResolver extends SchemaObjectTypeFieldResolver
{
    protected DirectiveTypeEnumTypeResolver $directiveTypeEnumTypeResolver;
    protected DirectiveRegistryInterface $directiveRegistry;

    #[Required]
    public function autowireFilterSystemDirectiveSchemaObjectTypeFieldResolver(
        DirectiveTypeEnumTypeResolver $directiveTypeEnumTypeResolver,
        DirectiveRegistryInterface $directiveRegistry,
    ): void {
        $this->directiveTypeEnumTypeResolver = $directiveTypeEnumTypeResolver;
        $this->directiveRegistry = $directiveRegistry;
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            SchemaObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'directives',
        ];
    }

    public function getPriorityToAttachToClasses(): int
    {
        // Higher priority => Process first
        return 100;
    }

    // /**
    //  * Only use this fieldResolver when parameter `ofTypes` is provided.
    //  * Otherwise, use the default implementation
    //  */
    // public function resolveCanProcess(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, array $fieldArgs = []): bool
    // {
    //     return $fieldName == 'directives' && isset($fieldArgs['ofTypes']);
    // }

    // public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    // {
    //     $descriptions = [
    //         'directives' => $this->translationAPI->__('All directives registered in the data graph, allowing to remove the system directives', 'graphql-api'),
    //     ];
    //     return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($objectTypeResolver, $fieldName);
    // }

    public function getSchemaFieldArgs(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($objectTypeResolver, $fieldName);
        switch ($fieldName) {
            case 'directives':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'ofTypes',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_ENUM,
                            SchemaDefinition::ARGNAME_IS_ARRAY => true,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('Include only directives of provided types', 'graphql-api'),
                            SchemaDefinition::ARGNAME_ENUM_NAME => $this->directiveTypeEnumTypeResolver->getTypeName(),
                            SchemaDefinition::ARGNAME_ENUM_VALUES => SchemaHelpers::convertToSchemaFieldArgEnumValueDefinitions(
                                $this->directiveTypeEnumTypeResolver
                            ),
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
        /** @var Schema */
        $schema = $object;
        switch ($fieldName) {
            case 'directives':
                $directiveIDs = $schema->getDirectiveIDs();
                if ($ofTypes = $fieldArgs['ofTypes'] ?? null) {
                    // Convert the enum from uppercase (as exposed in the API) to lowercase (as is its real value)
                    $ofTypes = array_map(
                        fn (string $enumValue) => $this->directiveTypeEnumTypeResolver->getEnumValueFromInput($enumValue),
                        $ofTypes
                    );
                    $ofTypeDirectiveResolvers = array_filter(
                        $this->directiveRegistry->getDirectiveResolvers(),
                        function ($directiveResolver) use ($ofTypes) {
                            return in_array($directiveResolver->getDirectiveType(), $ofTypes);
                        }
                    );
                    // Calculate the directive IDs
                    $ofTypeDirectiveIDs = array_map(
                        function ($directiveResolver) {
                            // To retrieve the ID, use the same method to calculate the ID
                            // used when creating a new Directive instance
                            // (which we can't do here, since it has side-effects)
                            $directiveSchemaDefinitionPath = [
                                SchemaDefinition::ARGNAME_GLOBAL_DIRECTIVES,
                                $directiveResolver->getDirectiveName(),
                            ];
                            return SchemaDefinitionHelpers::getID($directiveSchemaDefinitionPath);
                        },
                        $ofTypeDirectiveResolvers
                    );
                    return array_intersect(
                        $directiveIDs,
                        $ofTypeDirectiveIDs
                    );
                }
                return $directiveIDs;
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
