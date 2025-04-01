<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\UserMetaMutations\TypeResolvers\InputObjectType\RootAddUserMetaInputObjectTypeResolver;
use PoPCMSSchema\UserMetaMutations\TypeResolvers\InputObjectType\RootDeleteUserMetaInputObjectTypeResolver;
use PoPCMSSchema\UserMetaMutations\TypeResolvers\InputObjectType\RootSetUserMetaInputObjectTypeResolver;
use PoPCMSSchema\UserMetaMutations\TypeResolvers\InputObjectType\RootUpdateUserMetaInputObjectTypeResolver;
use PoPCMSSchema\UserMetaMutations\Module;
use PoPCMSSchema\UserMetaMutations\ModuleConfiguration;
use PoPCMSSchema\UserMetaMutations\MutationResolvers\AddUserMetaBulkOperationMutationResolver;
use PoPCMSSchema\UserMetaMutations\MutationResolvers\AddUserMetaMutationResolver;
use PoPCMSSchema\UserMetaMutations\MutationResolvers\DeleteUserMetaBulkOperationMutationResolver;
use PoPCMSSchema\UserMetaMutations\MutationResolvers\DeleteUserMetaMutationResolver;
use PoPCMSSchema\UserMetaMutations\MutationResolvers\PayloadableAddUserMetaBulkOperationMutationResolver;
use PoPCMSSchema\UserMetaMutations\MutationResolvers\PayloadableAddUserMetaMutationResolver;
use PoPCMSSchema\UserMetaMutations\MutationResolvers\PayloadableDeleteUserMetaBulkOperationMutationResolver;
use PoPCMSSchema\UserMetaMutations\MutationResolvers\PayloadableDeleteUserMetaMutationResolver;
use PoPCMSSchema\UserMetaMutations\MutationResolvers\PayloadableSetUserMetaBulkOperationMutationResolver;
use PoPCMSSchema\UserMetaMutations\MutationResolvers\PayloadableSetUserMetaMutationResolver;
use PoPCMSSchema\UserMetaMutations\MutationResolvers\PayloadableUpdateUserMetaBulkOperationMutationResolver;
use PoPCMSSchema\UserMetaMutations\MutationResolvers\PayloadableUpdateUserMetaMutationResolver;
use PoPCMSSchema\UserMetaMutations\MutationResolvers\SetUserMetaBulkOperationMutationResolver;
use PoPCMSSchema\UserMetaMutations\MutationResolvers\SetUserMetaMutationResolver;
use PoPCMSSchema\UserMetaMutations\MutationResolvers\UpdateUserMetaBulkOperationMutationResolver;
use PoPCMSSchema\UserMetaMutations\MutationResolvers\UpdateUserMetaMutationResolver;
use PoPCMSSchema\SchemaCommons\FieldResolvers\ObjectType\BulkOperationDecoratorObjectTypeFieldResolverTrait;
use PoPCMSSchema\SchemaCommons\FieldResolvers\ObjectType\MutationPayloadObjectsObjectTypeFieldResolverTrait;
use PoPCMSSchema\UserState\Checkpoints\UserLoggedInCheckpoint;
use PoP\ComponentModel\Checkpoints\CheckpointInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\Module as EngineModule;
use PoP\Engine\ModuleConfiguration as EngineModuleConfiguration;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\Root\App;

abstract class AbstractRootUserCRUDObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    use MutationPayloadObjectsObjectTypeFieldResolverTrait;
    use BulkOperationDecoratorObjectTypeFieldResolverTrait;

    private ?AddUserMetaMutationResolver $addUserMetaMutationResolver = null;
    private ?AddUserMetaBulkOperationMutationResolver $addUserMetaBulkOperationMutationResolver = null;
    private ?DeleteUserMetaMutationResolver $deleteUserMetaMutationResolver = null;
    private ?DeleteUserMetaBulkOperationMutationResolver $deleteUserMetaBulkOperationMutationResolver = null;
    private ?SetUserMetaMutationResolver $setUserMetaMutationResolver = null;
    private ?SetUserMetaBulkOperationMutationResolver $setUserMetaBulkOperationMutationResolver = null;
    private ?UpdateUserMetaMutationResolver $updateUserMetaMutationResolver = null;
    private ?UpdateUserMetaBulkOperationMutationResolver $updateUserMetaBulkOperationMutationResolver = null;
    private ?PayloadableDeleteUserMetaMutationResolver $payloadableDeleteUserMetaMutationResolver = null;
    private ?PayloadableDeleteUserMetaBulkOperationMutationResolver $payloadableDeleteUserMetaBulkOperationMutationResolver = null;
    private ?PayloadableSetUserMetaMutationResolver $payloadableSetUserMetaMutationResolver = null;
    private ?PayloadableSetUserMetaBulkOperationMutationResolver $payloadableSetUserMetaBulkOperationMutationResolver = null;
    private ?PayloadableUpdateUserMetaMutationResolver $payloadableUpdateUserMetaMutationResolver = null;
    private ?PayloadableUpdateUserMetaBulkOperationMutationResolver $payloadableUpdateUserMetaBulkOperationMutationResolver = null;
    private ?PayloadableAddUserMetaMutationResolver $payloadableAddUserMetaMutationResolver = null;
    private ?PayloadableAddUserMetaBulkOperationMutationResolver $payloadableAddUserMetaBulkOperationMutationResolver = null;
    private ?RootDeleteUserMetaInputObjectTypeResolver $rootDeleteUserMetaInputObjectTypeResolver = null;
    private ?RootSetUserMetaInputObjectTypeResolver $rootSetUserMetaInputObjectTypeResolver = null;
    private ?RootUpdateUserMetaInputObjectTypeResolver $rootUpdateUserMetaInputObjectTypeResolver = null;
    private ?RootAddUserMetaInputObjectTypeResolver $rootAddUserMetaInputObjectTypeResolver = null;
    private ?UserLoggedInCheckpoint $userLoggedInCheckpoint = null;

    final protected function getAddUserMetaMutationResolver(): AddUserMetaMutationResolver
    {
        if ($this->addUserMetaMutationResolver === null) {
            /** @var AddUserMetaMutationResolver */
            $addUserMetaMutationResolver = $this->instanceManager->getInstance(AddUserMetaMutationResolver::class);
            $this->addUserMetaMutationResolver = $addUserMetaMutationResolver;
        }
        return $this->addUserMetaMutationResolver;
    }
    final protected function getAddUserMetaBulkOperationMutationResolver(): AddUserMetaBulkOperationMutationResolver
    {
        if ($this->addUserMetaBulkOperationMutationResolver === null) {
            /** @var AddUserMetaBulkOperationMutationResolver */
            $addUserMetaBulkOperationMutationResolver = $this->instanceManager->getInstance(AddUserMetaBulkOperationMutationResolver::class);
            $this->addUserMetaBulkOperationMutationResolver = $addUserMetaBulkOperationMutationResolver;
        }
        return $this->addUserMetaBulkOperationMutationResolver;
    }
    final protected function getDeleteUserMetaMutationResolver(): DeleteUserMetaMutationResolver
    {
        if ($this->deleteUserMetaMutationResolver === null) {
            /** @var DeleteUserMetaMutationResolver */
            $deleteUserMetaMutationResolver = $this->instanceManager->getInstance(DeleteUserMetaMutationResolver::class);
            $this->deleteUserMetaMutationResolver = $deleteUserMetaMutationResolver;
        }
        return $this->deleteUserMetaMutationResolver;
    }
    final protected function getDeleteUserMetaBulkOperationMutationResolver(): DeleteUserMetaBulkOperationMutationResolver
    {
        if ($this->deleteUserMetaBulkOperationMutationResolver === null) {
            /** @var DeleteUserMetaBulkOperationMutationResolver */
            $deleteUserMetaBulkOperationMutationResolver = $this->instanceManager->getInstance(DeleteUserMetaBulkOperationMutationResolver::class);
            $this->deleteUserMetaBulkOperationMutationResolver = $deleteUserMetaBulkOperationMutationResolver;
        }
        return $this->deleteUserMetaBulkOperationMutationResolver;
    }
    final protected function getSetUserMetaMutationResolver(): SetUserMetaMutationResolver
    {
        if ($this->setUserMetaMutationResolver === null) {
            /** @var SetUserMetaMutationResolver */
            $setUserMetaMutationResolver = $this->instanceManager->getInstance(SetUserMetaMutationResolver::class);
            $this->setUserMetaMutationResolver = $setUserMetaMutationResolver;
        }
        return $this->setUserMetaMutationResolver;
    }
    final protected function getSetUserMetaBulkOperationMutationResolver(): SetUserMetaBulkOperationMutationResolver
    {
        if ($this->setUserMetaBulkOperationMutationResolver === null) {
            /** @var SetUserMetaBulkOperationMutationResolver */
            $setUserMetaBulkOperationMutationResolver = $this->instanceManager->getInstance(SetUserMetaBulkOperationMutationResolver::class);
            $this->setUserMetaBulkOperationMutationResolver = $setUserMetaBulkOperationMutationResolver;
        }
        return $this->setUserMetaBulkOperationMutationResolver;
    }
    final protected function getUpdateUserMetaMutationResolver(): UpdateUserMetaMutationResolver
    {
        if ($this->updateUserMetaMutationResolver === null) {
            /** @var UpdateUserMetaMutationResolver */
            $updateUserMetaMutationResolver = $this->instanceManager->getInstance(UpdateUserMetaMutationResolver::class);
            $this->updateUserMetaMutationResolver = $updateUserMetaMutationResolver;
        }
        return $this->updateUserMetaMutationResolver;
    }
    final protected function getUpdateUserMetaBulkOperationMutationResolver(): UpdateUserMetaBulkOperationMutationResolver
    {
        if ($this->updateUserMetaBulkOperationMutationResolver === null) {
            /** @var UpdateUserMetaBulkOperationMutationResolver */
            $updateUserMetaBulkOperationMutationResolver = $this->instanceManager->getInstance(UpdateUserMetaBulkOperationMutationResolver::class);
            $this->updateUserMetaBulkOperationMutationResolver = $updateUserMetaBulkOperationMutationResolver;
        }
        return $this->updateUserMetaBulkOperationMutationResolver;
    }
    final protected function getPayloadableDeleteUserMetaMutationResolver(): PayloadableDeleteUserMetaMutationResolver
    {
        if ($this->payloadableDeleteUserMetaMutationResolver === null) {
            /** @var PayloadableDeleteUserMetaMutationResolver */
            $payloadableDeleteUserMetaMutationResolver = $this->instanceManager->getInstance(PayloadableDeleteUserMetaMutationResolver::class);
            $this->payloadableDeleteUserMetaMutationResolver = $payloadableDeleteUserMetaMutationResolver;
        }
        return $this->payloadableDeleteUserMetaMutationResolver;
    }
    final protected function getPayloadableDeleteUserMetaBulkOperationMutationResolver(): PayloadableDeleteUserMetaBulkOperationMutationResolver
    {
        if ($this->payloadableDeleteUserMetaBulkOperationMutationResolver === null) {
            /** @var PayloadableDeleteUserMetaBulkOperationMutationResolver */
            $payloadableDeleteUserMetaBulkOperationMutationResolver = $this->instanceManager->getInstance(PayloadableDeleteUserMetaBulkOperationMutationResolver::class);
            $this->payloadableDeleteUserMetaBulkOperationMutationResolver = $payloadableDeleteUserMetaBulkOperationMutationResolver;
        }
        return $this->payloadableDeleteUserMetaBulkOperationMutationResolver;
    }
    final protected function getPayloadableSetUserMetaMutationResolver(): PayloadableSetUserMetaMutationResolver
    {
        if ($this->payloadableSetUserMetaMutationResolver === null) {
            /** @var PayloadableSetUserMetaMutationResolver */
            $payloadableSetUserMetaMutationResolver = $this->instanceManager->getInstance(PayloadableSetUserMetaMutationResolver::class);
            $this->payloadableSetUserMetaMutationResolver = $payloadableSetUserMetaMutationResolver;
        }
        return $this->payloadableSetUserMetaMutationResolver;
    }
    final protected function getPayloadableSetUserMetaBulkOperationMutationResolver(): PayloadableSetUserMetaBulkOperationMutationResolver
    {
        if ($this->payloadableSetUserMetaBulkOperationMutationResolver === null) {
            /** @var PayloadableSetUserMetaBulkOperationMutationResolver */
            $payloadableSetUserMetaBulkOperationMutationResolver = $this->instanceManager->getInstance(PayloadableSetUserMetaBulkOperationMutationResolver::class);
            $this->payloadableSetUserMetaBulkOperationMutationResolver = $payloadableSetUserMetaBulkOperationMutationResolver;
        }
        return $this->payloadableSetUserMetaBulkOperationMutationResolver;
    }
    final protected function getPayloadableUpdateUserMetaMutationResolver(): PayloadableUpdateUserMetaMutationResolver
    {
        if ($this->payloadableUpdateUserMetaMutationResolver === null) {
            /** @var PayloadableUpdateUserMetaMutationResolver */
            $payloadableUpdateUserMetaMutationResolver = $this->instanceManager->getInstance(PayloadableUpdateUserMetaMutationResolver::class);
            $this->payloadableUpdateUserMetaMutationResolver = $payloadableUpdateUserMetaMutationResolver;
        }
        return $this->payloadableUpdateUserMetaMutationResolver;
    }
    final protected function getPayloadableUpdateUserMetaBulkOperationMutationResolver(): PayloadableUpdateUserMetaBulkOperationMutationResolver
    {
        if ($this->payloadableUpdateUserMetaBulkOperationMutationResolver === null) {
            /** @var PayloadableUpdateUserMetaBulkOperationMutationResolver */
            $payloadableUpdateUserMetaBulkOperationMutationResolver = $this->instanceManager->getInstance(PayloadableUpdateUserMetaBulkOperationMutationResolver::class);
            $this->payloadableUpdateUserMetaBulkOperationMutationResolver = $payloadableUpdateUserMetaBulkOperationMutationResolver;
        }
        return $this->payloadableUpdateUserMetaBulkOperationMutationResolver;
    }
    final protected function getPayloadableAddUserMetaMutationResolver(): PayloadableAddUserMetaMutationResolver
    {
        if ($this->payloadableAddUserMetaMutationResolver === null) {
            /** @var PayloadableAddUserMetaMutationResolver */
            $payloadableAddUserMetaMutationResolver = $this->instanceManager->getInstance(PayloadableAddUserMetaMutationResolver::class);
            $this->payloadableAddUserMetaMutationResolver = $payloadableAddUserMetaMutationResolver;
        }
        return $this->payloadableAddUserMetaMutationResolver;
    }
    final protected function getPayloadableAddUserMetaBulkOperationMutationResolver(): PayloadableAddUserMetaBulkOperationMutationResolver
    {
        if ($this->payloadableAddUserMetaBulkOperationMutationResolver === null) {
            /** @var PayloadableAddUserMetaBulkOperationMutationResolver */
            $payloadableAddUserMetaBulkOperationMutationResolver = $this->instanceManager->getInstance(PayloadableAddUserMetaBulkOperationMutationResolver::class);
            $this->payloadableAddUserMetaBulkOperationMutationResolver = $payloadableAddUserMetaBulkOperationMutationResolver;
        }
        return $this->payloadableAddUserMetaBulkOperationMutationResolver;
    }
    final protected function getRootDeleteUserMetaInputObjectTypeResolver(): RootDeleteUserMetaInputObjectTypeResolver
    {
        if ($this->rootDeleteUserMetaInputObjectTypeResolver === null) {
            /** @var RootDeleteUserMetaInputObjectTypeResolver */
            $rootDeleteUserMetaInputObjectTypeResolver = $this->instanceManager->getInstance(RootDeleteUserMetaInputObjectTypeResolver::class);
            $this->rootDeleteUserMetaInputObjectTypeResolver = $rootDeleteUserMetaInputObjectTypeResolver;
        }
        return $this->rootDeleteUserMetaInputObjectTypeResolver;
    }
    final protected function getRootSetUserMetaInputObjectTypeResolver(): RootSetUserMetaInputObjectTypeResolver
    {
        if ($this->rootSetUserMetaInputObjectTypeResolver === null) {
            /** @var RootSetUserMetaInputObjectTypeResolver */
            $rootSetUserMetaInputObjectTypeResolver = $this->instanceManager->getInstance(RootSetUserMetaInputObjectTypeResolver::class);
            $this->rootSetUserMetaInputObjectTypeResolver = $rootSetUserMetaInputObjectTypeResolver;
        }
        return $this->rootSetUserMetaInputObjectTypeResolver;
    }
    final protected function getRootUpdateUserMetaInputObjectTypeResolver(): RootUpdateUserMetaInputObjectTypeResolver
    {
        if ($this->rootUpdateUserMetaInputObjectTypeResolver === null) {
            /** @var RootUpdateUserMetaInputObjectTypeResolver */
            $rootUpdateUserMetaInputObjectTypeResolver = $this->instanceManager->getInstance(RootUpdateUserMetaInputObjectTypeResolver::class);
            $this->rootUpdateUserMetaInputObjectTypeResolver = $rootUpdateUserMetaInputObjectTypeResolver;
        }
        return $this->rootUpdateUserMetaInputObjectTypeResolver;
    }
    final protected function getRootAddUserMetaInputObjectTypeResolver(): RootAddUserMetaInputObjectTypeResolver
    {
        if ($this->rootAddUserMetaInputObjectTypeResolver === null) {
            /** @var RootAddUserMetaInputObjectTypeResolver */
            $rootAddUserMetaInputObjectTypeResolver = $this->instanceManager->getInstance(RootAddUserMetaInputObjectTypeResolver::class);
            $this->rootAddUserMetaInputObjectTypeResolver = $rootAddUserMetaInputObjectTypeResolver;
        }
        return $this->rootAddUserMetaInputObjectTypeResolver;
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

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootObjectTypeResolver::class,
        ];
    }

    abstract protected function getUserEntityName(): string;

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        $userEntityName = $this->getUserEntityName();
        /** @var EngineModuleConfiguration */
        $engineModuleConfiguration = App::getModule(EngineModule::class)->getConfiguration();
        $disableRedundantRootTypeMutationFields = $engineModuleConfiguration->disableRedundantRootTypeMutationFields();
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $addFieldsToQueryPayloadableUserMetaMutations = $moduleConfiguration->addFieldsToQueryPayloadableUserMetaMutations();
        return array_merge(
            !$disableRedundantRootTypeMutationFields ? [
                'add' . $userEntityName . 'Meta',
                'add' . $userEntityName . 'Metas',
                'update' . $userEntityName . 'Meta',
                'update' . $userEntityName . 'Metas',
                'delete' . $userEntityName . 'Meta',
                'delete' . $userEntityName . 'Metas',
                'set' . $userEntityName . 'Meta',
                'set' . $userEntityName . 'Metas',
            ] : [],
            $addFieldsToQueryPayloadableUserMetaMutations && !$disableRedundantRootTypeMutationFields ? [
                'add' . $userEntityName . 'MetaMutationPayloadObjects',
                'update' . $userEntityName . 'MetaMutationPayloadObjects',
                'delete' . $userEntityName . 'MetaMutationPayloadObjects',
                'set' . $userEntityName . 'MetaMutationPayloadObjects',
            ] : [],
        );
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $userEntityName = $this->getUserEntityName();
        return match ($fieldName) {
            'add' . $userEntityName . 'Meta' => $this->__('Add meta to user', 'user-mutations'),
            'add' . $userEntityName . 'Metas' => $this->__('Add meta to users', 'user-mutations'),
            'update' . $userEntityName . 'Meta' => $this->__('Update meta from user', 'user-mutations'),
            'update' . $userEntityName . 'Metas' => $this->__('Update meta from users', 'user-mutations'),
            'delete' . $userEntityName . 'Meta' => $this->__('Delete meta from user', 'user-mutations'),
            'delete' . $userEntityName . 'Metas' => $this->__('Delete meta from users', 'user-mutations'),
            'set' . $userEntityName . 'Meta' => $this->__('Set meta on user', 'user-mutations'),
            'set' . $userEntityName . 'Metas' => $this->__('Set meta on users', 'user-mutations'),
            'add' . $userEntityName . 'MetaMutationPayloadObjects' => $this->__('Retrieve the payload objects from a recently-executed `addUserMeta` mutation', 'user-mutations'),
            'update' . $userEntityName . 'MetaMutationPayloadObjects' => $this->__('Retrieve the payload objects from a recently-executed `updateUserMeta` mutation', 'user-mutations'),
            'delete' . $userEntityName . 'MetaMutationPayloadObjects' => $this->__('Retrieve the payload objects from a recently-executed `deleteUserMeta` mutation', 'user-mutations'),
            'set' . $userEntityName . 'MetaMutationPayloadObjects' => $this->__('Retrieve the payload objects from a recently-executed `setUserMeta` mutation', 'user-mutations'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        $userEntityName = $this->getUserEntityName();
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableUserMetaMutations = $moduleConfiguration->usePayloadableUserMetaMutations();
        if (!$usePayloadableUserMetaMutations) {
            return match ($fieldName) {
                'add' . $userEntityName . 'Meta',
                'update' . $userEntityName . 'Meta',
                'delete' . $userEntityName . 'Meta',
                'set' . $userEntityName . 'Meta'
                    => SchemaTypeModifiers::NONE,
                'add' . $userEntityName . 'Metas',
                'update' . $userEntityName . 'Metas',
                'delete' . $userEntityName . 'Metas',
                'set' . $userEntityName . 'Metas'
                    => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
                default
                    => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
            };
        }

        if (
            in_array($fieldName, [
            'add' . $userEntityName . 'MetaMutationPayloadObjects',
            'update' . $userEntityName . 'MetaMutationPayloadObjects',
            'delete' . $userEntityName . 'MetaMutationPayloadObjects',
            'set' . $userEntityName . 'MetaMutationPayloadObjects',
            ])
        ) {
            return $this->getMutationPayloadObjectsFieldTypeModifiers();
        }

        return match ($fieldName) {
            'add' . $userEntityName . 'Meta',
            'update' . $userEntityName . 'Meta',
            'delete' . $userEntityName . 'Meta',
            'set' . $userEntityName . 'Meta'
                => SchemaTypeModifiers::NON_NULLABLE,
            'add' . $userEntityName . 'Metas',
            'update' . $userEntityName . 'Metas',
            'delete' . $userEntityName . 'Metas',
            'set' . $userEntityName . 'Metas'
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
        $userEntityName = $this->getUserEntityName();
        return match ($fieldName) {
            'add' . $userEntityName . 'Meta' => [
                'input' => $this->getRootAddUserMetaInputObjectTypeResolver(),
            ],
            'add' . $userEntityName . 'Metas'
                => $this->getBulkOperationFieldArgNameTypeResolvers($this->getRootAddUserMetaInputObjectTypeResolver()),
            'update' . $userEntityName . 'Meta' => [
                'input' => $this->getRootUpdateUserMetaInputObjectTypeResolver(),
            ],
            'update' . $userEntityName . 'Metas'
                => $this->getBulkOperationFieldArgNameTypeResolvers($this->getRootUpdateUserMetaInputObjectTypeResolver()),
            'delete' . $userEntityName . 'Meta' => [
                'input' => $this->getRootDeleteUserMetaInputObjectTypeResolver(),
            ],
            'delete' . $userEntityName . 'Metas'
                => $this->getBulkOperationFieldArgNameTypeResolvers($this->getRootDeleteUserMetaInputObjectTypeResolver()),
            'set' . $userEntityName . 'Meta' => [
                'input' => $this->getRootSetUserMetaInputObjectTypeResolver(),
            ],
            'set' . $userEntityName . 'Metas'
                => $this->getBulkOperationFieldArgNameTypeResolvers($this->getRootSetUserMetaInputObjectTypeResolver()),
            'add' . $userEntityName . 'MetaMutationPayloadObjects',
            'update' . $userEntityName . 'MetaMutationPayloadObjects',
            'delete' . $userEntityName . 'MetaMutationPayloadObjects',
            'set' . $userEntityName . 'MetaMutationPayloadObjects'
                => $this->getMutationPayloadObjectsFieldArgNameTypeResolvers(),
            default
                => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        $userEntityName = $this->getUserEntityName();
        if (
            in_array($fieldName, [
            'add' . $userEntityName . 'MetaMutationPayloadObjects',
            'update' . $userEntityName . 'MetaMutationPayloadObjects',
            'delete' . $userEntityName . 'MetaMutationPayloadObjects',
            'set' . $userEntityName . 'MetaMutationPayloadObjects',
            ])
        ) {
            return $this->getMutationPayloadObjectsFieldArgTypeModifiers($fieldArgName)
                ?? parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
        }

        if (
            in_array($fieldName, [
            'add' . $userEntityName . 'Metas',
            'update' . $userEntityName . 'Metas',
            'delete' . $userEntityName . 'Metas',
            'set' . $userEntityName . 'Metas',
            ])
        ) {
            return $this->getBulkOperationFieldArgTypeModifiers($fieldArgName)
                ?? parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
        }

        return match ([$fieldName => $fieldArgName]) {
            ['add' . $userEntityName . 'Meta' => 'input'],
            ['update' . $userEntityName . 'Meta' => 'input'],
            ['delete' . $userEntityName . 'Meta' => 'input'],
            ['set' . $userEntityName . 'Meta' => 'input']
                => SchemaTypeModifiers::MANDATORY,
            default => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldArgDefaultValue(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): mixed
    {
        $userEntityName = $this->getUserEntityName();
        if (
            in_array($fieldName, [
            'add' . $userEntityName . 'Metas',
            'update' . $userEntityName . 'Metas',
            'delete' . $userEntityName . 'Metas',
            'set' . $userEntityName . 'Metas',
            ])
        ) {
            return $this->getBulkOperationFieldArgDefaultValue($fieldArgName)
                ?? parent::getFieldArgDefaultValue($objectTypeResolver, $fieldName, $fieldArgName);
        }

        return parent::getFieldArgDefaultValue($objectTypeResolver, $fieldName, $fieldArgName);
    }

    public function getFieldMutationResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?MutationResolverInterface
    {
        $userEntityName = $this->getUserEntityName();
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableUserMetaMutations = $moduleConfiguration->usePayloadableUserMetaMutations();
        return match ($fieldName) {
            'add' . $userEntityName . 'Meta' => $usePayloadableUserMetaMutations
                ? $this->getPayloadableAddUserMetaMutationResolver()
                : $this->getAddUserMetaMutationResolver(),
            'add' . $userEntityName . 'Metas' => $usePayloadableUserMetaMutations
                ? $this->getPayloadableAddUserMetaBulkOperationMutationResolver()
                : $this->getAddUserMetaBulkOperationMutationResolver(),
            'update' . $userEntityName . 'Meta' => $usePayloadableUserMetaMutations
                ? $this->getPayloadableUpdateUserMetaMutationResolver()
                : $this->getUpdateUserMetaMutationResolver(),
            'update' . $userEntityName . 'Metas' => $usePayloadableUserMetaMutations
                ? $this->getPayloadableUpdateUserMetaBulkOperationMutationResolver()
                : $this->getUpdateUserMetaBulkOperationMutationResolver(),
            'delete' . $userEntityName . 'Meta' => $usePayloadableUserMetaMutations
                ? $this->getPayloadableDeleteUserMetaMutationResolver()
                : $this->getDeleteUserMetaMutationResolver(),
            'delete' . $userEntityName . 'Metas' => $usePayloadableUserMetaMutations
                ? $this->getPayloadableDeleteUserMetaBulkOperationMutationResolver()
                : $this->getDeleteUserMetaBulkOperationMutationResolver(),
            'set' . $userEntityName . 'Meta' => $usePayloadableUserMetaMutations
                ? $this->getPayloadableSetUserMetaMutationResolver()
                : $this->getSetUserMetaMutationResolver(),
            'set' . $userEntityName . 'Metas' => $usePayloadableUserMetaMutations
                ? $this->getPayloadableSetUserMetaBulkOperationMutationResolver()
                : $this->getSetUserMetaBulkOperationMutationResolver(),
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
        $usePayloadableUserMetaMutations = $moduleConfiguration->usePayloadableUserMetaMutations();
        if ($usePayloadableUserMetaMutations) {
            return $validationCheckpoints;
        }

        $userEntityName = $this->getUserEntityName();
        switch ($fieldDataAccessor->getFieldName()) {
            case 'add' . $userEntityName . 'Meta':
            case 'add' . $userEntityName . 'Metas':
            case 'update' . $userEntityName . 'Meta':
            case 'update' . $userEntityName . 'Metas':
            case 'delete' . $userEntityName . 'Meta':
            case 'delete' . $userEntityName . 'Metas':
            case 'set' . $userEntityName . 'Meta':
            case 'set' . $userEntityName . 'Metas':
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
        $userEntityName = $this->getUserEntityName();
        $fieldName = $fieldDataAccessor->getFieldName();
        switch ($fieldName) {
            case 'add' . $userEntityName . 'MetaMutationPayloadObjects':
            case 'update' . $userEntityName . 'MetaMutationPayloadObjects':
            case 'delete' . $userEntityName . 'MetaMutationPayloadObjects':
            case 'set' . $userEntityName . 'MetaMutationPayloadObjects':
                return $this->resolveMutationPayloadObjectsValue(
                    $objectTypeResolver,
                    $fieldDataAccessor,
                );
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
