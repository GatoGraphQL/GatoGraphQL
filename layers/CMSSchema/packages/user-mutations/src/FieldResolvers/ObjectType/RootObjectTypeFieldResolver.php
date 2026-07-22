<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\UserMutations\Module;
use PoPCMSSchema\UserMutations\ModuleConfiguration;
use PoPCMSSchema\UserMutations\MutationResolvers\CreateUserBulkOperationMutationResolver;
use PoPCMSSchema\UserMutations\MutationResolvers\CreateUserMutationResolver;
use PoPCMSSchema\UserMutations\MutationResolvers\DeleteUserBulkOperationMutationResolver;
use PoPCMSSchema\UserMutations\MutationResolvers\DeleteUserMutationResolver;
use PoPCMSSchema\UserMutations\MutationResolvers\PayloadableCreateUserBulkOperationMutationResolver;
use PoPCMSSchema\UserMutations\MutationResolvers\PayloadableCreateUserMutationResolver;
use PoPCMSSchema\UserMutations\MutationResolvers\PayloadableDeleteUserBulkOperationMutationResolver;
use PoPCMSSchema\UserMutations\MutationResolvers\PayloadableDeleteUserMutationResolver;
use PoPCMSSchema\UserMutations\MutationResolvers\PayloadableUpdateUserBulkOperationMutationResolver;
use PoPCMSSchema\UserMutations\MutationResolvers\PayloadableUpdateUserMutationResolver;
use PoPCMSSchema\UserMutations\MutationResolvers\UpdateUserBulkOperationMutationResolver;
use PoPCMSSchema\UserMutations\MutationResolvers\UpdateUserMutationResolver;
use PoPCMSSchema\UserMutations\TypeResolvers\InputObjectType\RootCreateUserInputObjectTypeResolver;
use PoPCMSSchema\UserMutations\TypeResolvers\InputObjectType\RootDeleteUserInputObjectTypeResolver;
use PoPCMSSchema\UserMutations\TypeResolvers\InputObjectType\RootUpdateUserInputObjectTypeResolver;
use PoPCMSSchema\UserMutations\TypeResolvers\ObjectType\RootCreateUserMutationPayloadObjectTypeResolver;
use PoPCMSSchema\UserMutations\TypeResolvers\ObjectType\RootDeleteUserMutationPayloadObjectTypeResolver;
use PoPCMSSchema\UserMutations\TypeResolvers\ObjectType\RootUpdateUserMutationPayloadObjectTypeResolver;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;
use PoPCMSSchema\SchemaCommons\FieldResolvers\ObjectType\BulkOperationDecoratorObjectTypeFieldResolverTrait;
use PoPCMSSchema\SchemaCommons\FieldResolvers\ObjectType\MutationPayloadObjectsObjectTypeFieldResolverTrait;
use PoPCMSSchema\UserState\Checkpoints\UserLoggedInCheckpoint;
use PoP\ComponentModel\Checkpoints\CheckpointInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\Engine\Module as EngineModule;
use PoP\Engine\ModuleConfiguration as EngineModuleConfiguration;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\Root\App;

class RootObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    use MutationPayloadObjectsObjectTypeFieldResolverTrait;
    use BulkOperationDecoratorObjectTypeFieldResolverTrait;

    private ?UserObjectTypeResolver $userObjectTypeResolver = null;
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;
    private ?UserLoggedInCheckpoint $userLoggedInCheckpoint = null;
    private ?CreateUserMutationResolver $createUserMutationResolver = null;
    private ?CreateUserBulkOperationMutationResolver $createUserBulkOperationMutationResolver = null;
    private ?UpdateUserMutationResolver $updateUserMutationResolver = null;
    private ?UpdateUserBulkOperationMutationResolver $updateUserBulkOperationMutationResolver = null;
    private ?DeleteUserMutationResolver $deleteUserMutationResolver = null;
    private ?DeleteUserBulkOperationMutationResolver $deleteUserBulkOperationMutationResolver = null;
    private ?PayloadableCreateUserMutationResolver $payloadableCreateUserMutationResolver = null;
    private ?PayloadableCreateUserBulkOperationMutationResolver $payloadableCreateUserBulkOperationMutationResolver = null;
    private ?PayloadableUpdateUserMutationResolver $payloadableUpdateUserMutationResolver = null;
    private ?PayloadableUpdateUserBulkOperationMutationResolver $payloadableUpdateUserBulkOperationMutationResolver = null;
    private ?PayloadableDeleteUserMutationResolver $payloadableDeleteUserMutationResolver = null;
    private ?PayloadableDeleteUserBulkOperationMutationResolver $payloadableDeleteUserBulkOperationMutationResolver = null;
    private ?RootCreateUserInputObjectTypeResolver $rootCreateUserInputObjectTypeResolver = null;
    private ?RootUpdateUserInputObjectTypeResolver $rootUpdateUserInputObjectTypeResolver = null;
    private ?RootDeleteUserInputObjectTypeResolver $rootDeleteUserInputObjectTypeResolver = null;
    private ?RootCreateUserMutationPayloadObjectTypeResolver $rootCreateUserMutationPayloadObjectTypeResolver = null;
    private ?RootUpdateUserMutationPayloadObjectTypeResolver $rootUpdateUserMutationPayloadObjectTypeResolver = null;
    private ?RootDeleteUserMutationPayloadObjectTypeResolver $rootDeleteUserMutationPayloadObjectTypeResolver = null;

    final protected function getUserObjectTypeResolver(): UserObjectTypeResolver
    {
        if ($this->userObjectTypeResolver === null) {
            /** @var UserObjectTypeResolver */
            $userObjectTypeResolver = $this->instanceManager->getInstance(UserObjectTypeResolver::class);
            $this->userObjectTypeResolver = $userObjectTypeResolver;
        }
        return $this->userObjectTypeResolver;
    }
    final protected function getBooleanScalarTypeResolver(): BooleanScalarTypeResolver
    {
        if ($this->booleanScalarTypeResolver === null) {
            /** @var BooleanScalarTypeResolver */
            $booleanScalarTypeResolver = $this->instanceManager->getInstance(BooleanScalarTypeResolver::class);
            $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
        }
        return $this->booleanScalarTypeResolver;
    }
    final protected function getUserLoggedInCheckpoint(): UserLoggedInCheckpoint
    {
        if ($this->userLoggedInCheckpoint === null) {
            /** @var UserLoggedInCheckpoint */
            $userLoggedInCheckpoint = $this->instanceManager->getInstance(UserLoggedInCheckpoint::class);
            $this->userLoggedInCheckpoint = $userLoggedInCheckpoint;
        }
        return $this->userLoggedInCheckpoint;
    }
    final protected function getCreateUserMutationResolver(): CreateUserMutationResolver
    {
        if ($this->createUserMutationResolver === null) {
            /** @var CreateUserMutationResolver */
            $createUserMutationResolver = $this->instanceManager->getInstance(CreateUserMutationResolver::class);
            $this->createUserMutationResolver = $createUserMutationResolver;
        }
        return $this->createUserMutationResolver;
    }
    final protected function getCreateUserBulkOperationMutationResolver(): CreateUserBulkOperationMutationResolver
    {
        if ($this->createUserBulkOperationMutationResolver === null) {
            /** @var CreateUserBulkOperationMutationResolver */
            $createUserBulkOperationMutationResolver = $this->instanceManager->getInstance(CreateUserBulkOperationMutationResolver::class);
            $this->createUserBulkOperationMutationResolver = $createUserBulkOperationMutationResolver;
        }
        return $this->createUserBulkOperationMutationResolver;
    }
    final protected function getUpdateUserMutationResolver(): UpdateUserMutationResolver
    {
        if ($this->updateUserMutationResolver === null) {
            /** @var UpdateUserMutationResolver */
            $updateUserMutationResolver = $this->instanceManager->getInstance(UpdateUserMutationResolver::class);
            $this->updateUserMutationResolver = $updateUserMutationResolver;
        }
        return $this->updateUserMutationResolver;
    }
    final protected function getUpdateUserBulkOperationMutationResolver(): UpdateUserBulkOperationMutationResolver
    {
        if ($this->updateUserBulkOperationMutationResolver === null) {
            /** @var UpdateUserBulkOperationMutationResolver */
            $updateUserBulkOperationMutationResolver = $this->instanceManager->getInstance(UpdateUserBulkOperationMutationResolver::class);
            $this->updateUserBulkOperationMutationResolver = $updateUserBulkOperationMutationResolver;
        }
        return $this->updateUserBulkOperationMutationResolver;
    }
    final protected function getDeleteUserMutationResolver(): DeleteUserMutationResolver
    {
        if ($this->deleteUserMutationResolver === null) {
            /** @var DeleteUserMutationResolver */
            $deleteUserMutationResolver = $this->instanceManager->getInstance(DeleteUserMutationResolver::class);
            $this->deleteUserMutationResolver = $deleteUserMutationResolver;
        }
        return $this->deleteUserMutationResolver;
    }
    final protected function getDeleteUserBulkOperationMutationResolver(): DeleteUserBulkOperationMutationResolver
    {
        if ($this->deleteUserBulkOperationMutationResolver === null) {
            /** @var DeleteUserBulkOperationMutationResolver */
            $deleteUserBulkOperationMutationResolver = $this->instanceManager->getInstance(DeleteUserBulkOperationMutationResolver::class);
            $this->deleteUserBulkOperationMutationResolver = $deleteUserBulkOperationMutationResolver;
        }
        return $this->deleteUserBulkOperationMutationResolver;
    }
    final protected function getPayloadableCreateUserMutationResolver(): PayloadableCreateUserMutationResolver
    {
        if ($this->payloadableCreateUserMutationResolver === null) {
            /** @var PayloadableCreateUserMutationResolver */
            $payloadableCreateUserMutationResolver = $this->instanceManager->getInstance(PayloadableCreateUserMutationResolver::class);
            $this->payloadableCreateUserMutationResolver = $payloadableCreateUserMutationResolver;
        }
        return $this->payloadableCreateUserMutationResolver;
    }
    final protected function getPayloadableCreateUserBulkOperationMutationResolver(): PayloadableCreateUserBulkOperationMutationResolver
    {
        if ($this->payloadableCreateUserBulkOperationMutationResolver === null) {
            /** @var PayloadableCreateUserBulkOperationMutationResolver */
            $payloadableCreateUserBulkOperationMutationResolver = $this->instanceManager->getInstance(PayloadableCreateUserBulkOperationMutationResolver::class);
            $this->payloadableCreateUserBulkOperationMutationResolver = $payloadableCreateUserBulkOperationMutationResolver;
        }
        return $this->payloadableCreateUserBulkOperationMutationResolver;
    }
    final protected function getPayloadableUpdateUserMutationResolver(): PayloadableUpdateUserMutationResolver
    {
        if ($this->payloadableUpdateUserMutationResolver === null) {
            /** @var PayloadableUpdateUserMutationResolver */
            $payloadableUpdateUserMutationResolver = $this->instanceManager->getInstance(PayloadableUpdateUserMutationResolver::class);
            $this->payloadableUpdateUserMutationResolver = $payloadableUpdateUserMutationResolver;
        }
        return $this->payloadableUpdateUserMutationResolver;
    }
    final protected function getPayloadableUpdateUserBulkOperationMutationResolver(): PayloadableUpdateUserBulkOperationMutationResolver
    {
        if ($this->payloadableUpdateUserBulkOperationMutationResolver === null) {
            /** @var PayloadableUpdateUserBulkOperationMutationResolver */
            $payloadableUpdateUserBulkOperationMutationResolver = $this->instanceManager->getInstance(PayloadableUpdateUserBulkOperationMutationResolver::class);
            $this->payloadableUpdateUserBulkOperationMutationResolver = $payloadableUpdateUserBulkOperationMutationResolver;
        }
        return $this->payloadableUpdateUserBulkOperationMutationResolver;
    }
    final protected function getPayloadableDeleteUserMutationResolver(): PayloadableDeleteUserMutationResolver
    {
        if ($this->payloadableDeleteUserMutationResolver === null) {
            /** @var PayloadableDeleteUserMutationResolver */
            $payloadableDeleteUserMutationResolver = $this->instanceManager->getInstance(PayloadableDeleteUserMutationResolver::class);
            $this->payloadableDeleteUserMutationResolver = $payloadableDeleteUserMutationResolver;
        }
        return $this->payloadableDeleteUserMutationResolver;
    }
    final protected function getPayloadableDeleteUserBulkOperationMutationResolver(): PayloadableDeleteUserBulkOperationMutationResolver
    {
        if ($this->payloadableDeleteUserBulkOperationMutationResolver === null) {
            /** @var PayloadableDeleteUserBulkOperationMutationResolver */
            $payloadableDeleteUserBulkOperationMutationResolver = $this->instanceManager->getInstance(PayloadableDeleteUserBulkOperationMutationResolver::class);
            $this->payloadableDeleteUserBulkOperationMutationResolver = $payloadableDeleteUserBulkOperationMutationResolver;
        }
        return $this->payloadableDeleteUserBulkOperationMutationResolver;
    }
    final protected function getRootCreateUserInputObjectTypeResolver(): RootCreateUserInputObjectTypeResolver
    {
        if ($this->rootCreateUserInputObjectTypeResolver === null) {
            /** @var RootCreateUserInputObjectTypeResolver */
            $rootCreateUserInputObjectTypeResolver = $this->instanceManager->getInstance(RootCreateUserInputObjectTypeResolver::class);
            $this->rootCreateUserInputObjectTypeResolver = $rootCreateUserInputObjectTypeResolver;
        }
        return $this->rootCreateUserInputObjectTypeResolver;
    }
    final protected function getRootUpdateUserInputObjectTypeResolver(): RootUpdateUserInputObjectTypeResolver
    {
        if ($this->rootUpdateUserInputObjectTypeResolver === null) {
            /** @var RootUpdateUserInputObjectTypeResolver */
            $rootUpdateUserInputObjectTypeResolver = $this->instanceManager->getInstance(RootUpdateUserInputObjectTypeResolver::class);
            $this->rootUpdateUserInputObjectTypeResolver = $rootUpdateUserInputObjectTypeResolver;
        }
        return $this->rootUpdateUserInputObjectTypeResolver;
    }
    final protected function getRootDeleteUserInputObjectTypeResolver(): RootDeleteUserInputObjectTypeResolver
    {
        if ($this->rootDeleteUserInputObjectTypeResolver === null) {
            /** @var RootDeleteUserInputObjectTypeResolver */
            $rootDeleteUserInputObjectTypeResolver = $this->instanceManager->getInstance(RootDeleteUserInputObjectTypeResolver::class);
            $this->rootDeleteUserInputObjectTypeResolver = $rootDeleteUserInputObjectTypeResolver;
        }
        return $this->rootDeleteUserInputObjectTypeResolver;
    }
    final protected function getRootCreateUserMutationPayloadObjectTypeResolver(): RootCreateUserMutationPayloadObjectTypeResolver
    {
        if ($this->rootCreateUserMutationPayloadObjectTypeResolver === null) {
            /** @var RootCreateUserMutationPayloadObjectTypeResolver */
            $rootCreateUserMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootCreateUserMutationPayloadObjectTypeResolver::class);
            $this->rootCreateUserMutationPayloadObjectTypeResolver = $rootCreateUserMutationPayloadObjectTypeResolver;
        }
        return $this->rootCreateUserMutationPayloadObjectTypeResolver;
    }
    final protected function getRootUpdateUserMutationPayloadObjectTypeResolver(): RootUpdateUserMutationPayloadObjectTypeResolver
    {
        if ($this->rootUpdateUserMutationPayloadObjectTypeResolver === null) {
            /** @var RootUpdateUserMutationPayloadObjectTypeResolver */
            $rootUpdateUserMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootUpdateUserMutationPayloadObjectTypeResolver::class);
            $this->rootUpdateUserMutationPayloadObjectTypeResolver = $rootUpdateUserMutationPayloadObjectTypeResolver;
        }
        return $this->rootUpdateUserMutationPayloadObjectTypeResolver;
    }
    final protected function getRootDeleteUserMutationPayloadObjectTypeResolver(): RootDeleteUserMutationPayloadObjectTypeResolver
    {
        if ($this->rootDeleteUserMutationPayloadObjectTypeResolver === null) {
            /** @var RootDeleteUserMutationPayloadObjectTypeResolver */
            $rootDeleteUserMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootDeleteUserMutationPayloadObjectTypeResolver::class);
            $this->rootDeleteUserMutationPayloadObjectTypeResolver = $rootDeleteUserMutationPayloadObjectTypeResolver;
        }
        return $this->rootDeleteUserMutationPayloadObjectTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootObjectTypeResolver::class,
        ];
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        /** @var EngineModuleConfiguration */
        $engineModuleConfiguration = App::getModule(EngineModule::class)->getConfiguration();
        $disableRedundantRootTypeMutationFields = $engineModuleConfiguration->disableRedundantRootTypeMutationFields();
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $addFieldsToQueryPayloadableUserMutations = $moduleConfiguration->addFieldsToQueryPayloadableUserMutations();
        return array_merge(
            [
                'createUser',
                'createUsers',
            ],
            !$disableRedundantRootTypeMutationFields ? [
                'updateUser',
                'updateUsers',
                'deleteUser',
                'deleteUsers',
            ] : [],
            $addFieldsToQueryPayloadableUserMutations ? [
                'createUserMutationPayloadObjects',
            ] : [],
            $addFieldsToQueryPayloadableUserMutations && !$disableRedundantRootTypeMutationFields ? [
                'updateUserMutationPayloadObjects',
                'deleteUserMutationPayloadObjects',
            ] : [],
        );
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'createUser' => $this->__('Create a user', 'gatographql'),
            'createUsers' => $this->__('Create users', 'gatographql'),
            'updateUser' => $this->__('Update a user', 'gatographql'),
            'updateUsers' => $this->__('Update users', 'gatographql'),
            'deleteUser' => $this->__('Delete a user', 'gatographql'),
            'deleteUsers' => $this->__('Delete users', 'gatographql'),
            'createUserMutationPayloadObjects' => $this->__('Retrieve the payload objects from a recently-executed `createUser` mutation', 'gatographql'),
            'updateUserMutationPayloadObjects' => $this->__('Retrieve the payload objects from a recently-executed `updateUser` mutation', 'gatographql'),
            'deleteUserMutationPayloadObjects' => $this->__('Retrieve the payload objects from a recently-executed `deleteUser` mutation', 'gatographql'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableUserMutations = $moduleConfiguration->usePayloadableUserMutations();
        if (!$usePayloadableUserMutations) {
            return match ($fieldName) {
                'createUser',
                'updateUser'
                    => SchemaTypeModifiers::NONE,
                'deleteUser'
                    => SchemaTypeModifiers::NON_NULLABLE,
                'createUsers',
                'updateUsers'
                    => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
                'deleteUsers'
                    => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
                default
                    => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
            };
        }

        if (
            in_array($fieldName, [
            'createUserMutationPayloadObjects',
            'updateUserMutationPayloadObjects',
            'deleteUserMutationPayloadObjects',
            ])
        ) {
            return $this->getMutationPayloadObjectsFieldTypeModifiers();
        }

        return match ($fieldName) {
            'createUser',
            'updateUser',
            'deleteUser'
                => SchemaTypeModifiers::NON_NULLABLE,
            'createUsers',
            'updateUsers',
            'deleteUsers'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
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
            'createUser' => [
                'input' => $this->getRootCreateUserInputObjectTypeResolver(),
            ],
            'createUsers' => $this->getBulkOperationFieldArgNameTypeResolvers($this->getRootCreateUserInputObjectTypeResolver()),
            'updateUser' => [
                'input' => $this->getRootUpdateUserInputObjectTypeResolver(),
            ],
            'updateUsers' => $this->getBulkOperationFieldArgNameTypeResolvers($this->getRootUpdateUserInputObjectTypeResolver()),
            'deleteUser' => [
                'input' => $this->getRootDeleteUserInputObjectTypeResolver(),
            ],
            'deleteUsers' => $this->getBulkOperationFieldArgNameTypeResolvers($this->getRootDeleteUserInputObjectTypeResolver()),
            'createUserMutationPayloadObjects',
            'updateUserMutationPayloadObjects',
            'deleteUserMutationPayloadObjects'
                => $this->getMutationPayloadObjectsFieldArgNameTypeResolvers(),
            default
                => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        if (
            in_array($fieldName, [
            'createUserMutationPayloadObjects',
            'updateUserMutationPayloadObjects',
            'deleteUserMutationPayloadObjects',
            ])
        ) {
            return $this->getMutationPayloadObjectsFieldArgTypeModifiers($fieldArgName)
                ?? parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
        }

        if (
            in_array($fieldName, [
            'createUsers',
            'updateUsers',
            'deleteUsers',
            ])
        ) {
            return $this->getBulkOperationFieldArgTypeModifiers($fieldArgName)
                ?? parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
        }

        return match ([$fieldName => $fieldArgName]) {
            ['createUser' => 'input'],
            ['updateUser' => 'input'],
            ['deleteUser' => 'input']
                => SchemaTypeModifiers::MANDATORY,
            default => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldArgDefaultValue(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): mixed
    {
        if (
            in_array($fieldName, [
            'createUsers',
            'updateUsers',
            'deleteUsers',
            ])
        ) {
            return $this->getBulkOperationFieldArgDefaultValue($fieldArgName)
                ?? parent::getFieldArgDefaultValue($objectTypeResolver, $fieldName, $fieldArgName);
        }

        return parent::getFieldArgDefaultValue($objectTypeResolver, $fieldName, $fieldArgName);
    }

    public function getFieldMutationResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?MutationResolverInterface
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableUserMutations = $moduleConfiguration->usePayloadableUserMutations();
        return match ($fieldName) {
            'createUser' => $usePayloadableUserMutations
                ? $this->getPayloadableCreateUserMutationResolver()
                : $this->getCreateUserMutationResolver(),
            'createUsers' => $usePayloadableUserMutations
                ? $this->getPayloadableCreateUserBulkOperationMutationResolver()
                : $this->getCreateUserBulkOperationMutationResolver(),
            'updateUser' => $usePayloadableUserMutations
                ? $this->getPayloadableUpdateUserMutationResolver()
                : $this->getUpdateUserMutationResolver(),
            'updateUsers' => $usePayloadableUserMutations
                ? $this->getPayloadableUpdateUserBulkOperationMutationResolver()
                : $this->getUpdateUserBulkOperationMutationResolver(),
            'deleteUser' => $usePayloadableUserMutations
                ? $this->getPayloadableDeleteUserMutationResolver()
                : $this->getDeleteUserMutationResolver(),
            'deleteUsers' => $usePayloadableUserMutations
                ? $this->getPayloadableDeleteUserBulkOperationMutationResolver()
                : $this->getDeleteUserBulkOperationMutationResolver(),
            default => parent::getFieldMutationResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableUserMutations = $moduleConfiguration->usePayloadableUserMutations();
        if ($usePayloadableUserMutations) {
            return match ($fieldName) {
                'createUser',
                'createUsers',
                'createUserMutationPayloadObjects'
                    => $this->getRootCreateUserMutationPayloadObjectTypeResolver(),
                'updateUser',
                'updateUsers',
                'updateUserMutationPayloadObjects'
                    => $this->getRootUpdateUserMutationPayloadObjectTypeResolver(),
                'deleteUser',
                'deleteUsers',
                'deleteUserMutationPayloadObjects'
                    => $this->getRootDeleteUserMutationPayloadObjectTypeResolver(),
                default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
            };
        }
        return match ($fieldName) {
            'createUser',
            'createUsers',
            'updateUser',
            'updateUsers'
                => $this->getUserObjectTypeResolver(),
            'deleteUser',
            'deleteUsers'
                => $this->getBooleanScalarTypeResolver(),
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
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
        $usePayloadableUserMutations = $moduleConfiguration->usePayloadableUserMutations();
        if ($usePayloadableUserMutations) {
            return $validationCheckpoints;
        }

        switch ($fieldDataAccessor->getFieldName()) {
            case 'createUser':
            case 'createUsers':
            case 'updateUser':
            case 'updateUsers':
            case 'deleteUser':
            case 'deleteUsers':
                $validationCheckpoints[] = $this->getUserLoggedInCheckpoint();
                break;
        }
        return $validationCheckpoints;
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $fieldName = $fieldDataAccessor->getFieldName();
        switch ($fieldName) {
            case 'createUserMutationPayloadObjects':
            case 'updateUserMutationPayloadObjects':
            case 'deleteUserMutationPayloadObjects':
                return $this->resolveMutationPayloadObjectsValue(
                    $objectTypeResolver,
                    $fieldDataAccessor,
                );
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
