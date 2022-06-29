<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\FieldResolvers\ObjectType;

use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoPCMSSchema\CustomPostMutations\MutationResolvers\MutationInputProperties;
use PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType\CustomPostUpdateFilterInputObjectTypeResolver;

abstract class AbstractCustomPostObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?CustomPostUpdateFilterInputObjectTypeResolver $customPostUpdateFilterInputObjectTypeResolver = null;

    final public function setCustomPostUpdateFilterInputObjectTypeResolver(CustomPostUpdateFilterInputObjectTypeResolver $customPostUpdateFilterInputObjectTypeResolver): void
    {
        $this->customPostUpdateFilterInputObjectTypeResolver = $customPostUpdateFilterInputObjectTypeResolver;
    }
    final protected function getCustomPostUpdateFilterInputObjectTypeResolver(): CustomPostUpdateFilterInputObjectTypeResolver
    {
        return $this->customPostUpdateFilterInputObjectTypeResolver ??= $this->instanceManager->getInstance(CustomPostUpdateFilterInputObjectTypeResolver::class);
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'update',
        ];
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'update' => $this->__('Update the custom post', 'custompost-mutations'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            'update' => [
                'input' => $this->getCustomPostUpdateFilterInputObjectTypeResolver(),
            ],
            default => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return match ([$fieldName => $fieldArgName]) {
            ['update' => 'input'] => SchemaTypeModifiers::MANDATORY,
            default => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    /**
     * Validated the mutation on the object because the ID
     * is obtained from the same object, so it's not originally
     * present in $withArgumentsAST
     */
    public function validateMutationOnObject(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName
    ): bool {
        switch ($fieldName) {
            case 'update':
                return true;
        }
        return parent::validateMutationOnObject($objectTypeResolver, $fieldName);
    }

    protected function getMutationFieldForObject(
        FieldInterface $mutationField,
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
    ): FieldInterface {
        $mutationField = parent::getMutationFieldForObject(
            $mutationField,
            $objectTypeResolver,
            $object,
            $fieldName
        );
        $post = $object;
        switch ($fieldName) {
            case 'update':
                $mutationField->addArgument(new Argument(MutationInputProperties::ID, new Literal($objectTypeResolver->getID($post), LocationHelper::getNonSpecificLocation()), LocationHelper::getNonSpecificLocation()));
                break;
        }

        return $mutationField;
    }
}
