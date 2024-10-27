<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\FieldResolvers\ObjectType\Extensions;

use GraphQLByPoP\GraphQLServer\FieldResolvers\ObjectType\SchemaObjectTypeFieldResolver;
use GraphQLByPoP\GraphQLServer\ObjectModels\Schema;
use GraphQLByPoP\GraphQLServer\Schema\SchemaDefinitionHelpers;
use GraphQLByPoP\GraphQLServer\TypeResolvers\EnumType\DirectiveKindEnumTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\SchemaObjectTypeResolver;
use PoPAPI\API\Schema\SchemaDefinition;
use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Registries\FieldDirectiveResolverRegistryInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

class FilterSystemDirectiveSchemaObjectTypeFieldResolver extends SchemaObjectTypeFieldResolver
{
    private ?DirectiveKindEnumTypeResolver $directiveKindEnumTypeResolver = null;
    private ?FieldDirectiveResolverRegistryInterface $fieldDirectiveResolverRegistry = null;

    final protected function getDirectiveKindEnumTypeResolver(): DirectiveKindEnumTypeResolver
    {
        if ($this->directiveKindEnumTypeResolver === null) {
            /** @var DirectiveKindEnumTypeResolver */
            $directiveKindEnumTypeResolver = $this->instanceManager->getInstance(DirectiveKindEnumTypeResolver::class);
            $this->directiveKindEnumTypeResolver = $directiveKindEnumTypeResolver;
        }
        return $this->directiveKindEnumTypeResolver;
    }
    final protected function getFieldDirectiveResolverRegistry(): FieldDirectiveResolverRegistryInterface
    {
        if ($this->fieldDirectiveResolverRegistry === null) {
            /** @var FieldDirectiveResolverRegistryInterface */
            $fieldDirectiveResolverRegistry = $this->instanceManager->getInstance(FieldDirectiveResolverRegistryInterface::class);
            $this->fieldDirectiveResolverRegistry = $fieldDirectiveResolverRegistry;
        }
        return $this->fieldDirectiveResolverRegistry;
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
            ['directives' => 'ofKinds'] => $this->__('Include only directives of provided types', 'gatographql'),
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

    /**
     * Since the return type is known for all the fields in this
     * FieldResolver, there's no need to validate them
     */
    public function validateResolvedFieldType(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
    ): bool {
        return false;
    }
}
