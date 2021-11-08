<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\FieldResolvers\ObjectType\Extensions;

use GraphQLByPoP\GraphQLServer\FieldResolvers\ObjectType\SchemaObjectTypeFieldResolver;
use GraphQLByPoP\GraphQLServer\ObjectModels\Schema;
use GraphQLByPoP\GraphQLServer\Schema\SchemaDefinitionHelpers;
use GraphQLByPoP\GraphQLServer\TypeResolvers\EnumType\DirectiveTypeEnumTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\SchemaObjectTypeResolver;
use PoP\API\Schema\SchemaDefinition;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\Registries\DirectiveRegistryInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class FilterSystemDirectiveSchemaObjectTypeFieldResolver extends SchemaObjectTypeFieldResolver
{
    private ?DirectiveTypeEnumTypeResolver $directiveTypeEnumTypeResolver = null;
    private ?DirectiveRegistryInterface $directiveRegistry = null;

    final public function setDirectiveTypeEnumTypeResolver(DirectiveTypeEnumTypeResolver $directiveTypeEnumTypeResolver): void
    {
        $this->directiveTypeEnumTypeResolver = $directiveTypeEnumTypeResolver;
    }
    final protected function getDirectiveTypeEnumTypeResolver(): DirectiveTypeEnumTypeResolver
    {
        return $this->directiveTypeEnumTypeResolver ??= $this->instanceManager->getInstance(DirectiveTypeEnumTypeResolver::class);
    }
    final public function setDirectiveRegistry(DirectiveRegistryInterface $directiveRegistry): void
    {
        $this->directiveRegistry = $directiveRegistry;
    }
    final protected function getDirectiveRegistry(): DirectiveRegistryInterface
    {
        return $this->directiveRegistry ??= $this->instanceManager->getInstance(DirectiveRegistryInterface::class);
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
    // public function resolveCanProcess(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, array $fieldArgs): bool
    // {
    //     return $fieldName == 'directives' && isset($fieldArgs['ofTypes']);
    // }

    // public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    // {
    //     $descriptions = [
    //         'directives' => $this->getTranslationAPI()->__('All directives registered in the data graph, allowing to remove the system directives', 'graphql-api'),
    //     ];
    //     return $descriptions[$fieldName] ?? parent::getFieldDescription($objectTypeResolver, $fieldName);
    // }

    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            'directives' => [
                'ofTypes' => $this->getDirectiveTypeEnumTypeResolver(),
            ],
            default => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?string
    {
        return match ([$fieldName => $fieldArgName]) {
            ['directives' => 'ofTypes'] => $this->getTranslationAPI()->__('Include only directives of provided types', 'graphql-api'),
            default => parent::getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return match ([$fieldName => $fieldArgName]) {
            ['directives' => 'ofTypes'] => SchemaTypeModifiers::IS_ARRAY,
            default => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
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
        array $fieldArgs,
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
                    $ofTypeDirectiveResolvers = array_filter(
                        $this->getDirectiveRegistry()->getDirectiveResolvers(),
                        fn (DirectiveResolverInterface $directiveResolver) => in_array($directiveResolver->getDirectiveType(), $ofTypes)
                    );
                    // Calculate the directive IDs
                    $ofTypeDirectiveIDs = array_map(
                        function (DirectiveResolverInterface $directiveResolver): string {
                            // To retrieve the ID, use the same method to calculate the ID
                            // used when creating a new Directive instance
                            // (which we can't do here, since it has side-effects)
                            $directiveSchemaDefinitionPath = [
                                SchemaDefinition::GLOBAL_DIRECTIVES,
                                $directiveResolver->getDirectiveName(),
                            ];
                            return SchemaDefinitionHelpers::getSchemaDefinitionReferenceObjectID($directiveSchemaDefinitionPath);
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
