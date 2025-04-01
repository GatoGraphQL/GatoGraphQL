<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\UserMetaMutations\Module;
use PoPCMSSchema\UserMetaMutations\ModuleConfiguration;
use PoPCMSSchema\UserMetaMutations\MutationResolvers\AddUserMetaMutationResolver;
use PoPCMSSchema\UserMetaMutations\MutationResolvers\DeleteUserMetaMutationResolver;
use PoPCMSSchema\UserMetaMutations\MutationResolvers\PayloadableAddUserMetaMutationResolver;
use PoPCMSSchema\UserMetaMutations\MutationResolvers\PayloadableDeleteUserMetaMutationResolver;
use PoPCMSSchema\UserMetaMutations\MutationResolvers\PayloadableSetUserMetaMutationResolver;
use PoPCMSSchema\UserMetaMutations\MutationResolvers\PayloadableUpdateUserMetaMutationResolver;
use PoPCMSSchema\UserMetaMutations\MutationResolvers\SetUserMetaMutationResolver;
use PoPCMSSchema\UserMetaMutations\MutationResolvers\UpdateUserMetaMutationResolver;
use PoPCMSSchema\UserMetaMutations\TypeResolvers\InputObjectType\UserAddMetaInputObjectTypeResolver;
use PoPCMSSchema\UserMetaMutations\TypeResolvers\InputObjectType\UserDeleteMetaInputObjectTypeResolver;
use PoPCMSSchema\UserMetaMutations\TypeResolvers\InputObjectType\UserSetMetaInputObjectTypeResolver;
use PoPCMSSchema\UserMetaMutations\TypeResolvers\InputObjectType\UserUpdateMetaInputObjectTypeResolver;
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

abstract class AbstractUserObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?UserLoggedInCheckpoint $userLoggedInCheckpoint = null;
    private ?UserAddMetaInputObjectTypeResolver $userAddMetaInputObjectTypeResolver = null;
    private ?UserDeleteMetaInputObjectTypeResolver $userDeleteMetaInputObjectTypeResolver = null;
    private ?UserSetMetaInputObjectTypeResolver $userSetMetaInputObjectTypeResolver = null;
    private ?UserUpdateMetaInputObjectTypeResolver $userUpdateMetaInputObjectTypeResolver = null;
    private ?AddUserMetaMutationResolver $addUserMetaMutationResolver = null;
    private ?DeleteUserMetaMutationResolver $deleteUserMetaMutationResolver = null;
    private ?SetUserMetaMutationResolver $setUserMetaMutationResolver = null;
    private ?UpdateUserMetaMutationResolver $updateUserMetaMutationResolver = null;
    private ?PayloadableDeleteUserMetaMutationResolver $payloadableDeleteUserMetaMutationResolver = null;
    private ?PayloadableSetUserMetaMutationResolver $payloadableSetUserMetaMutationResolver = null;
    private ?PayloadableUpdateUserMetaMutationResolver $payloadableUpdateUserMetaMutationResolver = null;
    private ?PayloadableAddUserMetaMutationResolver $payloadableAddUserMetaMutationResolver = null;

    final protected function getUserLoggedInCheckpoint(): UserLoggedInCheckpoint
    {
        if ($this->userLoggedInCheckpoint === null) {
            /** @var UserLoggedInCheckpoint */
            $userLoggedInCheckpoint = $this->instanceManager->getInstance(UserLoggedInCheckpoint::class);
            $this->userLoggedInCheckpoint = $userLoggedInCheckpoint;
        }
        return $this->userLoggedInCheckpoint;
    }
    final protected function getUserAddMetaInputObjectTypeResolver(): UserAddMetaInputObjectTypeResolver
    {
        if ($this->userAddMetaInputObjectTypeResolver === null) {
            /** @var UserAddMetaInputObjectTypeResolver */
            $userAddMetaInputObjectTypeResolver = $this->instanceManager->getInstance(UserAddMetaInputObjectTypeResolver::class);
            $this->userAddMetaInputObjectTypeResolver = $userAddMetaInputObjectTypeResolver;
        }
        return $this->userAddMetaInputObjectTypeResolver;
    }
    final protected function getUserDeleteMetaInputObjectTypeResolver(): UserDeleteMetaInputObjectTypeResolver
    {
        if ($this->userDeleteMetaInputObjectTypeResolver === null) {
            /** @var UserDeleteMetaInputObjectTypeResolver */
            $userDeleteMetaInputObjectTypeResolver = $this->instanceManager->getInstance(UserDeleteMetaInputObjectTypeResolver::class);
            $this->userDeleteMetaInputObjectTypeResolver = $userDeleteMetaInputObjectTypeResolver;
        }
        return $this->userDeleteMetaInputObjectTypeResolver;
    }
    final protected function getUserSetMetaInputObjectTypeResolver(): UserSetMetaInputObjectTypeResolver
    {
        if ($this->userSetMetaInputObjectTypeResolver === null) {
            /** @var UserSetMetaInputObjectTypeResolver */
            $userSetMetaInputObjectTypeResolver = $this->instanceManager->getInstance(UserSetMetaInputObjectTypeResolver::class);
            $this->userSetMetaInputObjectTypeResolver = $userSetMetaInputObjectTypeResolver;
        }
        return $this->userSetMetaInputObjectTypeResolver;
    }
    final protected function getUserUpdateMetaInputObjectTypeResolver(): UserUpdateMetaInputObjectTypeResolver
    {
        if ($this->userUpdateMetaInputObjectTypeResolver === null) {
            /** @var UserUpdateMetaInputObjectTypeResolver */
            $userUpdateMetaInputObjectTypeResolver = $this->instanceManager->getInstance(UserUpdateMetaInputObjectTypeResolver::class);
            $this->userUpdateMetaInputObjectTypeResolver = $userUpdateMetaInputObjectTypeResolver;
        }
        return $this->userUpdateMetaInputObjectTypeResolver;
    }
    final protected function getAddUserMetaMutationResolver(): AddUserMetaMutationResolver
    {
        if ($this->addUserMetaMutationResolver === null) {
            /** @var AddUserMetaMutationResolver */
            $addUserMetaMutationResolver = $this->instanceManager->getInstance(AddUserMetaMutationResolver::class);
            $this->addUserMetaMutationResolver = $addUserMetaMutationResolver;
        }
        return $this->addUserMetaMutationResolver;
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
    final protected function getSetUserMetaMutationResolver(): SetUserMetaMutationResolver
    {
        if ($this->setUserMetaMutationResolver === null) {
            /** @var SetUserMetaMutationResolver */
            $setUserMetaMutationResolver = $this->instanceManager->getInstance(SetUserMetaMutationResolver::class);
            $this->setUserMetaMutationResolver = $setUserMetaMutationResolver;
        }
        return $this->setUserMetaMutationResolver;
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
    final protected function getPayloadableDeleteUserMetaMutationResolver(): PayloadableDeleteUserMetaMutationResolver
    {
        if ($this->payloadableDeleteUserMetaMutationResolver === null) {
            /** @var PayloadableDeleteUserMetaMutationResolver */
            $payloadableDeleteUserMetaMutationResolver = $this->instanceManager->getInstance(PayloadableDeleteUserMetaMutationResolver::class);
            $this->payloadableDeleteUserMetaMutationResolver = $payloadableDeleteUserMetaMutationResolver;
        }
        return $this->payloadableDeleteUserMetaMutationResolver;
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
    final protected function getPayloadableUpdateUserMetaMutationResolver(): PayloadableUpdateUserMetaMutationResolver
    {
        if ($this->payloadableUpdateUserMetaMutationResolver === null) {
            /** @var PayloadableUpdateUserMetaMutationResolver */
            $payloadableUpdateUserMetaMutationResolver = $this->instanceManager->getInstance(PayloadableUpdateUserMetaMutationResolver::class);
            $this->payloadableUpdateUserMetaMutationResolver = $payloadableUpdateUserMetaMutationResolver;
        }
        return $this->payloadableUpdateUserMetaMutationResolver;
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
            'addMeta' => $this->__('Add a user meta entry', 'usermeta-mutations'),
            'deleteMeta' => $this->__('Delete a user meta entry', 'usermeta-mutations'),
            'setMeta' => $this->__('Set meta entries to a a user', 'usermeta-mutations'),
            'updateMeta' => $this->__('Update a user meta entry', 'usermeta-mutations'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableUserMetaMutations = $moduleConfiguration->usePayloadableUserMetaMutations();
        if (!$usePayloadableUserMetaMutations) {
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
                'input' => $this->getUserAddMetaInputObjectTypeResolver(),
            ],
            'deleteMeta' => [
                'input' => $this->getUserDeleteMetaInputObjectTypeResolver(),
            ],
            'setMeta' => [
                'input' => $this->getUserSetMetaInputObjectTypeResolver(),
            ],
            'updateMeta' => [
                'input' => $this->getUserUpdateMetaInputObjectTypeResolver(),
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
        $user = $object;
        switch ($field->getName()) {
            case 'addMeta':
            case 'deleteMeta':
            case 'setMeta':
            case 'updateMeta':
                $fieldArgsForMutationForObject['input']->{MutationInputProperties::ID} = $objectTypeResolver->getID($user);
                break;
        }
        return $fieldArgsForMutationForObject;
    }

    public function getFieldMutationResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?MutationResolverInterface
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableUserMetaMutations = $moduleConfiguration->usePayloadableUserMetaMutations();
        return match ($fieldName) {
            'addMeta' => $usePayloadableUserMetaMutations
                ? $this->getPayloadableAddUserMetaMutationResolver()
                : $this->getAddUserMetaMutationResolver(),
            'updateMeta' => $usePayloadableUserMetaMutations
                ? $this->getPayloadableUpdateUserMetaMutationResolver()
                : $this->getUpdateUserMetaMutationResolver(),
            'deleteMeta' => $usePayloadableUserMetaMutations
                ? $this->getPayloadableDeleteUserMetaMutationResolver()
                : $this->getDeleteUserMetaMutationResolver(),
            'setMeta' => $usePayloadableUserMetaMutations
                ? $this->getPayloadableSetUserMetaMutationResolver()
                : $this->getSetUserMetaMutationResolver(),
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
