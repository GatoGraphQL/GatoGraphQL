<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CategoryMetaMutations\Module;
use PoPCMSSchema\CategoryMetaMutations\ModuleConfiguration;
use PoPCMSSchema\CategoryMetaMutations\MutationResolvers\AddCategoryTermMetaMutationResolver;
use PoPCMSSchema\CategoryMetaMutations\MutationResolvers\DeleteCategoryTermMetaMutationResolver;
use PoPCMSSchema\CategoryMetaMutations\MutationResolvers\PayloadableAddCategoryTermMetaMutationResolver;
use PoPCMSSchema\CategoryMetaMutations\MutationResolvers\PayloadableDeleteCategoryTermMetaMutationResolver;
use PoPCMSSchema\CategoryMetaMutations\MutationResolvers\PayloadableSetCategoryTermMetaMutationResolver;
use PoPCMSSchema\CategoryMetaMutations\MutationResolvers\PayloadableUpdateCategoryTermMetaMutationResolver;
use PoPCMSSchema\CategoryMetaMutations\MutationResolvers\SetCategoryTermMetaMutationResolver;
use PoPCMSSchema\CategoryMetaMutations\MutationResolvers\UpdateCategoryTermMetaMutationResolver;
use PoPCMSSchema\CategoryMetaMutations\TypeResolvers\InputObjectType\CategoryTermAddMetaInputObjectTypeResolver;
use PoPCMSSchema\CategoryMetaMutations\TypeResolvers\InputObjectType\CategoryTermDeleteMetaInputObjectTypeResolver;
use PoPCMSSchema\CategoryMetaMutations\TypeResolvers\InputObjectType\CategoryTermSetMetaInputObjectTypeResolver;
use PoPCMSSchema\CategoryMetaMutations\TypeResolvers\InputObjectType\CategoryTermUpdateMetaInputObjectTypeResolver;
use PoPCMSSchema\MetaMutations\Constants\MutationInputProperties;
use PoPCMSSchema\UserState\Checkpoints\UserLoggedInCheckpoint;
use PoP\ComponentModel\App;
use PoP\ComponentModel\Checkpoints\CheckpointInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

abstract class AbstractCategoryObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?UserLoggedInCheckpoint $userLoggedInCheckpoint = null;
    private ?CategoryTermAddMetaInputObjectTypeResolver $categoryTermAddMetaInputObjectTypeResolver = null;
    private ?CategoryTermDeleteMetaInputObjectTypeResolver $categoryTermDeleteMetaInputObjectTypeResolver = null;
    private ?CategoryTermSetMetaInputObjectTypeResolver $categoryTermSetMetaInputObjectTypeResolver = null;
    private ?CategoryTermUpdateMetaInputObjectTypeResolver $categoryTermUpdateMetaInputObjectTypeResolver = null;
    private ?AddCategoryTermMetaMutationResolver $addCategoryTermMetaMutationResolver = null;
    private ?DeleteCategoryTermMetaMutationResolver $deleteCategoryTermMetaMutationResolver = null;
    private ?SetCategoryTermMetaMutationResolver $setCategoryTermMetaMutationResolver = null;
    private ?UpdateCategoryTermMetaMutationResolver $updateCategoryTermMetaMutationResolver = null;
    private ?PayloadableDeleteCategoryTermMetaMutationResolver $payloadableDeleteCategoryTermMetaMutationResolver = null;
    private ?PayloadableSetCategoryTermMetaMutationResolver $payloadableSetCategoryTermMetaMutationResolver = null;
    private ?PayloadableUpdateCategoryTermMetaMutationResolver $payloadableUpdateCategoryTermMetaMutationResolver = null;
    private ?PayloadableAddCategoryTermMetaMutationResolver $payloadableAddCategoryTermMetaMutationResolver = null;

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
    final protected function getAddCategoryTermMetaMutationResolver(): AddCategoryTermMetaMutationResolver
    {
        if ($this->addCategoryTermMetaMutationResolver === null) {
            /** @var AddCategoryTermMetaMutationResolver */
            $addCategoryTermMetaMutationResolver = $this->instanceManager->getInstance(AddCategoryTermMetaMutationResolver::class);
            $this->addCategoryTermMetaMutationResolver = $addCategoryTermMetaMutationResolver;
        }
        return $this->addCategoryTermMetaMutationResolver;
    }
    final protected function getDeleteCategoryTermMetaMutationResolver(): DeleteCategoryTermMetaMutationResolver
    {
        if ($this->deleteCategoryTermMetaMutationResolver === null) {
            /** @var DeleteCategoryTermMetaMutationResolver */
            $deleteCategoryTermMetaMutationResolver = $this->instanceManager->getInstance(DeleteCategoryTermMetaMutationResolver::class);
            $this->deleteCategoryTermMetaMutationResolver = $deleteCategoryTermMetaMutationResolver;
        }
        return $this->deleteCategoryTermMetaMutationResolver;
    }
    final protected function getSetCategoryTermMetaMutationResolver(): SetCategoryTermMetaMutationResolver
    {
        if ($this->setCategoryTermMetaMutationResolver === null) {
            /** @var SetCategoryTermMetaMutationResolver */
            $setCategoryTermMetaMutationResolver = $this->instanceManager->getInstance(SetCategoryTermMetaMutationResolver::class);
            $this->setCategoryTermMetaMutationResolver = $setCategoryTermMetaMutationResolver;
        }
        return $this->setCategoryTermMetaMutationResolver;
    }
    final protected function getUpdateCategoryTermMetaMutationResolver(): UpdateCategoryTermMetaMutationResolver
    {
        if ($this->updateCategoryTermMetaMutationResolver === null) {
            /** @var UpdateCategoryTermMetaMutationResolver */
            $updateCategoryTermMetaMutationResolver = $this->instanceManager->getInstance(UpdateCategoryTermMetaMutationResolver::class);
            $this->updateCategoryTermMetaMutationResolver = $updateCategoryTermMetaMutationResolver;
        }
        return $this->updateCategoryTermMetaMutationResolver;
    }
    final protected function getPayloadableDeleteCategoryTermMetaMutationResolver(): PayloadableDeleteCategoryTermMetaMutationResolver
    {
        if ($this->payloadableDeleteCategoryTermMetaMutationResolver === null) {
            /** @var PayloadableDeleteCategoryTermMetaMutationResolver */
            $payloadableDeleteCategoryTermMetaMutationResolver = $this->instanceManager->getInstance(PayloadableDeleteCategoryTermMetaMutationResolver::class);
            $this->payloadableDeleteCategoryTermMetaMutationResolver = $payloadableDeleteCategoryTermMetaMutationResolver;
        }
        return $this->payloadableDeleteCategoryTermMetaMutationResolver;
    }
    final protected function getPayloadableSetCategoryTermMetaMutationResolver(): PayloadableSetCategoryTermMetaMutationResolver
    {
        if ($this->payloadableSetCategoryTermMetaMutationResolver === null) {
            /** @var PayloadableSetCategoryTermMetaMutationResolver */
            $payloadableSetCategoryTermMetaMutationResolver = $this->instanceManager->getInstance(PayloadableSetCategoryTermMetaMutationResolver::class);
            $this->payloadableSetCategoryTermMetaMutationResolver = $payloadableSetCategoryTermMetaMutationResolver;
        }
        return $this->payloadableSetCategoryTermMetaMutationResolver;
    }
    final protected function getPayloadableUpdateCategoryTermMetaMutationResolver(): PayloadableUpdateCategoryTermMetaMutationResolver
    {
        if ($this->payloadableUpdateCategoryTermMetaMutationResolver === null) {
            /** @var PayloadableUpdateCategoryTermMetaMutationResolver */
            $payloadableUpdateCategoryTermMetaMutationResolver = $this->instanceManager->getInstance(PayloadableUpdateCategoryTermMetaMutationResolver::class);
            $this->payloadableUpdateCategoryTermMetaMutationResolver = $payloadableUpdateCategoryTermMetaMutationResolver;
        }
        return $this->payloadableUpdateCategoryTermMetaMutationResolver;
    }
    final protected function getPayloadableAddCategoryTermMetaMutationResolver(): PayloadableAddCategoryTermMetaMutationResolver
    {
        if ($this->payloadableAddCategoryTermMetaMutationResolver === null) {
            /** @var PayloadableAddCategoryTermMetaMutationResolver */
            $payloadableAddCategoryTermMetaMutationResolver = $this->instanceManager->getInstance(PayloadableAddCategoryTermMetaMutationResolver::class);
            $this->payloadableAddCategoryTermMetaMutationResolver = $payloadableAddCategoryTermMetaMutationResolver;
        }
        return $this->payloadableAddCategoryTermMetaMutationResolver;
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            'addMeta',
            'deleteMeta',
            'setMeta',
            'updateMeta',
        ];
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'addMeta' => $this->__('Add a category term meta entry', 'categorymeta-mutations'),
            'deleteMeta' => $this->__('Delete a category term meta entry', 'categorymeta-mutations'),
            'setMeta' => $this->__('Set meta entries to a a category term', 'categorymeta-mutations'),
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
                'addMeta',
                'deleteMeta',
                'setMeta',
                'updateMeta'
                    => SchemaTypeModifiers::NONE,
                default
                    => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
            };
        }
        return match ($fieldName) {
            'addMeta',
            'deleteMeta',
            'setMeta',
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
            'addMeta' => [
                'input' => $this->getCategoryTermAddMetaInputObjectTypeResolver(),
            ],
            'deleteMeta' => [
                'input' => $this->getCategoryTermDeleteMetaInputObjectTypeResolver(),
            ],
            'setMeta' => [
                'input' => $this->getCategoryTermSetMetaInputObjectTypeResolver(),
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
            ['addMeta' => 'input'],
            ['deleteMeta' => 'input'],
            ['setMeta' => 'input'],
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
            case 'addMeta':
            case 'deleteMeta':
            case 'setMeta':
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
            case 'addMeta':
            case 'deleteMeta':
            case 'setMeta':
            case 'updateMeta':
                $fieldArgsForMutationForObject['input']->{MutationInputProperties::ID} = $objectTypeResolver->getID($category);
                break;
        }
        return $fieldArgsForMutationForObject;
    }

    public function getFieldMutationResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?MutationResolverInterface
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableCategoryMetaMutations = $moduleConfiguration->usePayloadableCategoryMetaMutations();
        return match ($fieldName) {
            'addMeta' => $usePayloadableCategoryMetaMutations
                ? $this->getPayloadableAddCategoryTermMetaMutationResolver()
                : $this->getAddCategoryTermMetaMutationResolver(),
            'updateMeta' => $usePayloadableCategoryMetaMutations
                ? $this->getPayloadableUpdateCategoryTermMetaMutationResolver()
                : $this->getUpdateCategoryTermMetaMutationResolver(),
            'deleteMeta' => $usePayloadableCategoryMetaMutations
                ? $this->getPayloadableDeleteCategoryTermMetaMutationResolver()
                : $this->getDeleteCategoryTermMetaMutationResolver(),
            'setMeta' => $usePayloadableCategoryMetaMutations
                ? $this->getPayloadableSetCategoryTermMetaMutationResolver()
                : $this->getSetCategoryTermMetaMutationResolver(),
            default => parent::getFieldMutationResolver($objectTypeResolver, $fieldName),
        };
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
            case 'addMeta':
            case 'deleteMeta':
            case 'setMeta':
            case 'updateMeta':
                $validationCheckpoints[] = $this->getUserLoggedInCheckpoint();
                break;
        }
        return $validationCheckpoints;
    }
}
