<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\FieldResolvers\Extensions;

use PoP\API\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaHelpers;
use PoP\ComponentModel\Schema\TypeCastingHelpers;
use PoP\Translation\Facades\TranslationAPIFacade;
use GraphQLByPoP\GraphQLServer\Enums\DirectiveTypeEnum;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use GraphQLByPoP\GraphQLServer\Schema\SchemaDefinitionHelpers;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use GraphQLByPoP\GraphQLServer\TypeResolvers\SchemaTypeResolver;
use GraphQLByPoP\GraphQLServer\FieldResolvers\SchemaFieldResolver;
use PoP\ComponentModel\Facades\Registries\DirectiveRegistryFacade;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;

class FilterSystemDirectiveSchemaFieldResolver extends SchemaFieldResolver
{
    public function getClassesToAttachTo(): array
    {
        return array(SchemaTypeResolver::class);
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'directives',
        ];
    }

    public function getPriorityToAttachClasses(): ?int
    {
        // Higher priority => Process first
        return 100;
    }

    // /**
    //  * Only use this fieldResolver when parameter `ofTypes` is provided.
    //  * Otherwise, use the default implementation
    //  *
    //  * @param TypeResolverInterface $typeResolver
    //  * @param string $fieldName
    //  * @param array<string, mixed> $fieldArgs
    //  * @return boolean
    //  */
    // public function resolveCanProcess(TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): bool
    // {
    //     return $fieldName == 'directives' && isset($fieldArgs['ofTypes']);
    // }

    // public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    // {
    //     $translationAPI = TranslationAPIFacade::getInstance();
    //     $descriptions = [
    //         'directives' => $translationAPI->__('All directives registered in the data graph, allowing to remove the system directives', 'graphql-api'),
    //     ];
    //     return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    // }

    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($typeResolver, $fieldName);
        $translationAPI = TranslationAPIFacade::getInstance();
        $instanceManager = InstanceManagerFacade::getInstance();
        switch ($fieldName) {
            case 'directives':
                /**
                 * @var DirectiveTypeEnum
                 */
                $directiveTypeEnum = $instanceManager->getInstance(DirectiveTypeEnum::class);
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'ofTypes',
                            SchemaDefinition::ARGNAME_TYPE => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_ENUM),
                            SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('Include only directives of provided types', 'graphql-api'),
                            // SchemaDefinition::ARGNAME_MANDATORY => true,
                            // SchemaDefinition::ARGNAME_DEFAULT_VALUE => [
                            //     DirectiveTypes::QUERY,
                            // ],
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
     * @return mixed
     */
    public function resolveValue(
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ) {
        $schema = $resultItem;
        switch ($fieldName) {
            case 'directives':
                $directiveIDs = $schema->getDirectiveIDs();
                if ($ofTypes = $fieldArgs['ofTypes'] ?? null) {
                    $instanceManager = InstanceManagerFacade::getInstance();
                    /**
                     * @var DirectiveTypeEnum
                     */
                    $directiveTypeEnum = $instanceManager->getInstance(DirectiveTypeEnum::class);
                    // Convert the enum from uppercase (as exposed in the API) to lowercase (as is its real value)
                    $ofTypes = array_map(
                        [$directiveTypeEnum, 'getCoreValue'],
                        $ofTypes
                    );
                    $directiveRegistry = DirectiveRegistryFacade::getInstance();
                    $ofTypeDirectiveResolverClasses = array_filter(
                        $directiveRegistry->getDirectiveResolvers(),
                        function ($directiveResolver) use ($ofTypes) {
                            return in_array($directiveResolver->getDirectiveType(), $ofTypes);
                        }
                    );
                    // Calculate the directive IDs
                    $ofTypeDirectiveIDs = array_map(
                        function ($directiveResolverClass) {
                            // To retrieve the ID, use the same method to calculate the ID
                            // used when creating a new Directive instance
                            // (which we can't do here, since it has side-effects)
                            $directiveSchemaDefinitionPath = [
                                SchemaDefinition::ARGNAME_GLOBAL_DIRECTIVES,
                                $directiveResolverClass::getDirectiveName(),
                            ];
                            return SchemaDefinitionHelpers::getID($directiveSchemaDefinitionPath);
                        },
                        $ofTypeDirectiveResolverClasses
                    );
                    return array_intersect(
                        $directiveIDs,
                        $ofTypeDirectiveIDs
                    );
                }
                return $directiveIDs;
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
