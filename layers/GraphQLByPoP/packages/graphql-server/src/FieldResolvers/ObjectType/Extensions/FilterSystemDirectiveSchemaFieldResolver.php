<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\FieldResolvers\ObjectType\Extensions;

use PoP\API\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaHelpers;
use GraphQLByPoP\GraphQLServer\Enums\DirectiveTypeEnum;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use GraphQLByPoP\GraphQLServer\Schema\SchemaDefinitionHelpers;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\SchemaTypeResolver;
use GraphQLByPoP\GraphQLServer\FieldResolvers\ObjectType\SchemaFieldResolver;
use PoP\ComponentModel\Facades\Registries\DirectiveRegistryFacade;

class FilterSystemDirectiveSchemaFieldResolver extends SchemaFieldResolver
{
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            SchemaTypeResolver::class,
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
                /**
                 * @var DirectiveTypeEnum
                 */
                $directiveTypeEnum = $this->instanceManager->getInstance(DirectiveTypeEnum::class);
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'ofTypes',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_ENUM,
                            SchemaDefinition::ARGNAME_IS_ARRAY => true,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('Include only directives of provided types', 'graphql-api'),
                            SchemaDefinition::ARGNAME_ENUM_NAME => $directiveTypeEnum->getName(),
                            SchemaDefinition::ARGNAME_ENUM_VALUES => SchemaHelpers::convertToSchemaFieldArgEnumValueDefinitions(
                                $directiveTypeEnum->getValues()
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
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $schema = $resultItem;
        switch ($fieldName) {
            case 'directives':
                $directiveIDs = $schema->getDirectiveIDs();
                if ($ofTypes = $fieldArgs['ofTypes'] ?? null) {
                    /**
                     * @var DirectiveTypeEnum
                     */
                    $directiveTypeEnum = $this->instanceManager->getInstance(DirectiveTypeEnum::class);
                    // Convert the enum from uppercase (as exposed in the API) to lowercase (as is its real value)
                    $ofTypes = array_map(
                        [$directiveTypeEnum, 'getCoreValue'],
                        $ofTypes
                    );
                    $directiveRegistry = DirectiveRegistryFacade::getInstance();
                    $ofTypeDirectiveResolvers = array_filter(
                        $directiveRegistry->getDirectiveResolvers(),
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

        return parent::resolveValue($objectTypeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
