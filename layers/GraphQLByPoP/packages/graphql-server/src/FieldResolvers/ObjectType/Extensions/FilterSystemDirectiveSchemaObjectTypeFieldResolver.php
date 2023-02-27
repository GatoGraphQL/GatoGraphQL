<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\FieldResolvers\ObjectType\Extensions;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use GraphQLByPoP\GraphQLServer\FieldResolvers\ObjectType\SchemaObjectTypeFieldResolver;
use GraphQLByPoP\GraphQLServer\ObjectModels\Schema;
use GraphQLByPoP\GraphQLServer\Schema\SchemaDefinitionHelpers;
use GraphQLByPoP\GraphQLServer\TypeResolvers\EnumType\DirectiveKindEnumTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\SchemaObjectTypeResolver;
use PoPAPI\API\Schema\SchemaDefinition;
use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;
use PoP\ComponentModel\Registries\FieldDirectiveResolverRegistryInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class FilterSystemDirectiveSchemaObjectTypeFieldResolver extends SchemaObjectTypeFieldResolver
{
    private ?DirectiveKindEnumTypeResolver $directiveKindEnumTypeResolver = null;
    private ?FieldDirectiveResolverRegistryInterface $fieldDirectiveResolverRegistry = null;

    final public function setDirectiveKindEnumTypeResolver(DirectiveKindEnumTypeResolver $directiveKindEnumTypeResolver): void
    {
        $this->directiveKindEnumTypeResolver = $directiveKindEnumTypeResolver;
    }
    final protected function getDirectiveKindEnumTypeResolver(): DirectiveKindEnumTypeResolver
    {
        /** @var DirectiveKindEnumTypeResolver */
        return $this->directiveKindEnumTypeResolver ??= $this->instanceManager->getInstance(DirectiveKindEnumTypeResolver::class);
    }
    final public function setFieldDirectiveResolverRegistry(FieldDirectiveResolverRegistryInterface $fieldDirectiveResolverRegistry): void
    {
        $this->fieldDirectiveResolverRegistry = $fieldDirectiveResolverRegistry;
    }
    final protected function getFieldDirectiveResolverRegistry(): FieldDirectiveResolverRegistryInterface
    {
        /** @var FieldDirectiveResolverRegistryInterface */
        return $this->fieldDirectiveResolverRegistry ??= $this->instanceManager->getInstance(FieldDirectiveResolverRegistryInterface::class);
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            SchemaObjectTypeResolver::class,
        ];
    }

    /**
     * @return string[]
     */
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

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            'directives' => [
                'ofKinds' => $this->getDirectiveKindEnumTypeResolver(),
            ],
            default => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?string
    {
        return match ([$fieldName => $fieldArgName]) {
            ['directives' => 'ofKinds'] => $this->__('Include only directives of provided types', 'graphql-api'),
            default => parent::getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return match ([$fieldName => $fieldArgName]) {
            ['directives' => 'ofKinds'] => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        /** @var Schema */
        $schema = $object;
        switch ($fieldDataAccessor->getFieldName()) {
            case 'directives':
                $directiveIDs = $schema->getDirectiveIDs();
                if ($ofKinds = $fieldDataAccessor->getValue('ofKinds')) {
                    $ofTypeFieldDirectiveResolvers = array_filter(
                        $this->getFieldDirectiveResolverRegistry()->getFieldDirectiveResolvers(),
                        fn (FieldDirectiveResolverInterface $directiveResolver) => in_array($directiveResolver->getDirectiveKind(), $ofKinds)
                    );
                    // Calculate the directive IDs
                    $ofTypeDirectiveIDs = array_map(
                        function (FieldDirectiveResolverInterface $directiveResolver): string {
                            // To retrieve the ID, use the same method to calculate the ID
                            // used when creating a new Directive instance
                            // (which we can't do here, since it has side-effects)
                            $directiveSchemaDefinitionPath = [
                                SchemaDefinition::GLOBAL_DIRECTIVES,
                                $directiveResolver->getDirectiveName(),
                            ];
                            return SchemaDefinitionHelpers::getSchemaDefinitionReferenceObjectID($directiveSchemaDefinitionPath);
                        },
                        $ofTypeFieldDirectiveResolvers
                    );
                    return array_values(array_intersect(
                        $directiveIDs,
                        $ofTypeDirectiveIDs
                    ));
                }
                return $directiveIDs;
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
