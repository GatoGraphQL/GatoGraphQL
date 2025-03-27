<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMetaMutations\FieldResolvers\ObjectType;

use PoP\ComponentModel\App;
use PoP\ComponentModel\Checkpoints\CheckpointInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoPCMSSchema\CategoryMetaMutations\Module;
use PoPCMSSchema\CategoryMetaMutations\ModuleConfiguration;
use PoPCMSSchema\CategoryMetaMutations\TypeResolvers\InputObjectType\CategoryTermAddMetaInputObjectTypeResolver;
use PoPCMSSchema\CategoryMetaMutations\TypeResolvers\InputObjectType\CategoryTermDeleteMetaInputObjectTypeResolver;
use PoPCMSSchema\CategoryMetaMutations\TypeResolvers\InputObjectType\CategoryTermSetMetaInputObjectTypeResolver;
use PoPCMSSchema\CategoryMetaMutations\TypeResolvers\InputObjectType\CategoryTermUpdateMetaInputObjectTypeResolver;
use PoPCMSSchema\MetaMutations\Constants\MutationInputProperties;
use PoPCMSSchema\UserState\Checkpoints\UserLoggedInCheckpoint;

abstract class AbstractCategoryObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?UserLoggedInCheckpoint $userLoggedInCheckpoint = null;
    private ?CategoryTermAddMetaInputObjectTypeResolver $categoryTermAddMetaInputObjectTypeResolver = null;
    private ?CategoryTermDeleteMetaInputObjectTypeResolver $categoryTermDeleteMetaInputObjectTypeResolver = null;
    private ?CategoryTermSetMetaInputObjectTypeResolver $categoryTermSetMetaInputObjectTypeResolver = null;
    private ?CategoryTermUpdateMetaInputObjectTypeResolver $categoryTermUpdateMetaInputObjectTypeResolver = null;

    final protected function getUserLoggedInCheckpoint(): UserLoggedInCheckpoint
    {
        if ($this->userLoggedInCheckpoint === null) {
            /** @var UserLoggedInCheckpoint */
            $userLoggedInCheckpoint = $this->instanceManager->getInstance(UserLoggedInCheckpoint::class);
            $this->userLoggedInCheckpoint = $userLoggedInCheckpoint;
        }
        return $this->userLoggedInCheckpoint;
    }
    final protected function getCategoryTermAddMetaInputObjectTypeResolver(): CategoryTermAddMetaInputObjectTypeResolver
    {
        if ($this->categoryTermAddMetaInputObjectTypeResolver === null) {
            /** @var CategoryTermAddMetaInputObjectTypeResolver */
            $categoryTermAddMetaInputObjectTypeResolver = $this->instanceManager->getInstance(CategoryTermAddMetaInputObjectTypeResolver::class);
            $this->categoryTermAddMetaInputObjectTypeResolver = $categoryTermAddMetaInputObjectTypeResolver;
        }
        return $this->categoryTermAddMetaInputObjectTypeResolver;
    }
    final protected function getCategoryTermDeleteMetaInputObjectTypeResolver(): CategoryTermDeleteMetaInputObjectTypeResolver
    {
        if ($this->categoryTermDeleteMetaInputObjectTypeResolver === null) {
            /** @var CategoryTermDeleteMetaInputObjectTypeResolver */
            $categoryTermDeleteMetaInputObjectTypeResolver = $this->instanceManager->getInstance(CategoryTermDeleteMetaInputObjectTypeResolver::class);
            $this->categoryTermDeleteMetaInputObjectTypeResolver = $categoryTermDeleteMetaInputObjectTypeResolver;
        }
        return $this->categoryTermDeleteMetaInputObjectTypeResolver;
    }
    final protected function getCategoryTermSetMetaInputObjectTypeResolver(): CategoryTermSetMetaInputObjectTypeResolver
    {
        if ($this->categoryTermSetMetaInputObjectTypeResolver === null) {
            /** @var CategoryTermSetMetaInputObjectTypeResolver */
            $categoryTermSetMetaInputObjectTypeResolver = $this->instanceManager->getInstance(CategoryTermSetMetaInputObjectTypeResolver::class);
            $this->categoryTermSetMetaInputObjectTypeResolver = $categoryTermSetMetaInputObjectTypeResolver;
        }
        return $this->categoryTermSetMetaInputObjectTypeResolver;
    }
    final protected function getCategoryTermUpdateMetaInputObjectTypeResolver(): CategoryTermUpdateMetaInputObjectTypeResolver
    {
        if ($this->categoryTermUpdateMetaInputObjectTypeResolver === null) {
            /** @var CategoryTermUpdateMetaInputObjectTypeResolver */
            $categoryTermUpdateMetaInputObjectTypeResolver = $this->instanceManager->getInstance(CategoryTermUpdateMetaInputObjectTypeResolver::class);
            $this->categoryTermUpdateMetaInputObjectTypeResolver = $categoryTermUpdateMetaInputObjectTypeResolver;
        }
        return $this->categoryTermUpdateMetaInputObjectTypeResolver;
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            'deleteMeta',
            'updateMeta',
        ];
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'deleteMeta' => $this->__('Delete a category term meta entry', 'categorymeta-mutations'),
            'updateMeta' => $this->__('Update a category term meta entry', 'categorymeta-mutations'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableCategoryMetaMutations = $moduleConfiguration->usePayloadableCategoryMetaMutations();
        if (!$usePayloadableCategoryMetaMutations) {
            return match ($fieldName) {
                'deleteMeta',
                'updateMeta'
                    => SchemaTypeModifiers::NONE,
                default
                    => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
            };
        }
        return match ($fieldName) {
            'deleteMeta',
            'updateMeta'
                => SchemaTypeModifiers::NON_NULLABLE,
            default
                => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            'deleteMeta' => [
                'input' => $this->getCategoryTermDeleteMetaInputObjectTypeResolver(),
            ],
            'updateMeta' => [
                'input' => $this->getCategoryTermUpdateMetaInputObjectTypeResolver(),
            ],
            default => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return match ([$fieldName => $fieldArgName]) {
            ['deleteMeta' => 'input'],
            ['updateMeta' => 'input']
                => SchemaTypeModifiers::MANDATORY,
            default
                => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    /**
     * Validated the mutation on the object because the ID
     * is obtained from the same object, so it's not originally
     * present in the field argument in the query
     */
    public function validateMutationOnObject(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName
    ): bool {
        switch ($fieldName) {
            case 'deleteMeta':
            case 'updateMeta':
                return true;
        }
        return parent::validateMutationOnObject($objectTypeResolver, $fieldName);
    }

    /**
     * @param array<string,mixed> $fieldArgsForMutationForObject
     * @return array<string,mixed>
     */
    public function prepareFieldArgsForMutationForObject(
        array $fieldArgsForMutationForObject,
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
        object $object,
    ): array {
        $fieldArgsForMutationForObject = parent::prepareFieldArgsForMutationForObject(
            $fieldArgsForMutationForObject,
            $objectTypeResolver,
            $field,
            $object,
        );
        $category = $object;
        switch ($field->getName()) {
            case 'deleteMeta':
            case 'updateMeta':
                $fieldArgsForMutationForObject['input']->{MutationInputProperties::ID} = $objectTypeResolver->getID($category);
                break;
        }
        return $fieldArgsForMutationForObject;
    }

    /**
     * @return CheckpointInterface[]
     */
    public function getValidationCheckpoints(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldDataAccessorInterface $fieldDataAccessor,
        object $object,
    ): array {
        $validationCheckpoints = parent::getValidationCheckpoints(
            $objectTypeResolver,
            $fieldDataAccessor,
            $object,
        );

        /**
         * For Payloadable: The "User Logged-in" checkpoint validation is not added,
         * instead this validation is executed inside the mutation, so the error
         * shows up in the Payload
         *
         * @var ModuleConfiguration
         */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableCategoryMetaMutations = $moduleConfiguration->usePayloadableCategoryMetaMutations();
        if ($usePayloadableCategoryMetaMutations) {
            return $validationCheckpoints;
        }

        switch ($fieldDataAccessor->getFieldName()) {
            case 'deleteMeta':
            case 'updateMeta':
                $validationCheckpoints[] = $this->getUserLoggedInCheckpoint();
                break;
        }
        return $validationCheckpoints;
    }
}
