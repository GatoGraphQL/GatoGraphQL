<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\UserMutations\Constants\MutationInputProperties;
use PoPCMSSchema\UserMutations\Module;
use PoPCMSSchema\UserMutations\ModuleConfiguration;
use PoPCMSSchema\UserMutations\MutationResolvers\DeleteUserMutationResolver;
use PoPCMSSchema\UserMutations\MutationResolvers\PayloadableDeleteUserMutationResolver;
use PoPCMSSchema\UserMutations\MutationResolvers\PayloadableUpdateUserMutationResolver;
use PoPCMSSchema\UserMutations\MutationResolvers\UpdateUserMutationResolver;
use PoPCMSSchema\UserMutations\TypeResolvers\InputObjectType\UserDeleteInputObjectTypeResolver;
use PoPCMSSchema\UserMutations\TypeResolvers\InputObjectType\UserUpdateInputObjectTypeResolver;
use PoPCMSSchema\UserMutations\TypeResolvers\ObjectType\UserDeleteMutationPayloadObjectTypeResolver;
use PoPCMSSchema\UserMutations\TypeResolvers\ObjectType\UserUpdateMutationPayloadObjectTypeResolver;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;
use PoPCMSSchema\UserState\Checkpoints\UserLoggedInCheckpoint;
use PoP\ComponentModel\App;
use PoP\ComponentModel\Checkpoints\CheckpointInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use stdClass;

class UserObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?UserObjectTypeResolver $userObjectTypeResolver = null;
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;
    private ?UserLoggedInCheckpoint $userLoggedInCheckpoint = null;
    private ?UpdateUserMutationResolver $updateUserMutationResolver = null;
    private ?PayloadableUpdateUserMutationResolver $payloadableUpdateUserMutationResolver = null;
    private ?UserUpdateInputObjectTypeResolver $userUpdateInputObjectTypeResolver = null;
    private ?UserUpdateMutationPayloadObjectTypeResolver $userUpdateMutationPayloadObjectTypeResolver = null;
    private ?DeleteUserMutationResolver $deleteUserMutationResolver = null;
    private ?PayloadableDeleteUserMutationResolver $payloadableDeleteUserMutationResolver = null;
    private ?UserDeleteInputObjectTypeResolver $userDeleteInputObjectTypeResolver = null;
    private ?UserDeleteMutationPayloadObjectTypeResolver $userDeleteMutationPayloadObjectTypeResolver = null;

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
    final protected function getUpdateUserMutationResolver(): UpdateUserMutationResolver
    {
        if ($this->updateUserMutationResolver === null) {
            /** @var UpdateUserMutationResolver */
            $updateUserMutationResolver = $this->instanceManager->getInstance(UpdateUserMutationResolver::class);
            $this->updateUserMutationResolver = $updateUserMutationResolver;
        }
        return $this->updateUserMutationResolver;
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
    final protected function getUserUpdateInputObjectTypeResolver(): UserUpdateInputObjectTypeResolver
    {
        if ($this->userUpdateInputObjectTypeResolver === null) {
            /** @var UserUpdateInputObjectTypeResolver */
            $userUpdateInputObjectTypeResolver = $this->instanceManager->getInstance(UserUpdateInputObjectTypeResolver::class);
            $this->userUpdateInputObjectTypeResolver = $userUpdateInputObjectTypeResolver;
        }
        return $this->userUpdateInputObjectTypeResolver;
    }
    final protected function getUserUpdateMutationPayloadObjectTypeResolver(): UserUpdateMutationPayloadObjectTypeResolver
    {
        if ($this->userUpdateMutationPayloadObjectTypeResolver === null) {
            /** @var UserUpdateMutationPayloadObjectTypeResolver */
            $userUpdateMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(UserUpdateMutationPayloadObjectTypeResolver::class);
            $this->userUpdateMutationPayloadObjectTypeResolver = $userUpdateMutationPayloadObjectTypeResolver;
        }
        return $this->userUpdateMutationPayloadObjectTypeResolver;
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
    final protected function getPayloadableDeleteUserMutationResolver(): PayloadableDeleteUserMutationResolver
    {
        if ($this->payloadableDeleteUserMutationResolver === null) {
            /** @var PayloadableDeleteUserMutationResolver */
            $payloadableDeleteUserMutationResolver = $this->instanceManager->getInstance(PayloadableDeleteUserMutationResolver::class);
            $this->payloadableDeleteUserMutationResolver = $payloadableDeleteUserMutationResolver;
        }
        return $this->payloadableDeleteUserMutationResolver;
    }
    final protected function getUserDeleteInputObjectTypeResolver(): UserDeleteInputObjectTypeResolver
    {
        if ($this->userDeleteInputObjectTypeResolver === null) {
            /** @var UserDeleteInputObjectTypeResolver */
            $userDeleteInputObjectTypeResolver = $this->instanceManager->getInstance(UserDeleteInputObjectTypeResolver::class);
            $this->userDeleteInputObjectTypeResolver = $userDeleteInputObjectTypeResolver;
        }
        return $this->userDeleteInputObjectTypeResolver;
    }
    final protected function getUserDeleteMutationPayloadObjectTypeResolver(): UserDeleteMutationPayloadObjectTypeResolver
    {
        if ($this->userDeleteMutationPayloadObjectTypeResolver === null) {
            /** @var UserDeleteMutationPayloadObjectTypeResolver */
            $userDeleteMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(UserDeleteMutationPayloadObjectTypeResolver::class);
            $this->userDeleteMutationPayloadObjectTypeResolver = $userDeleteMutationPayloadObjectTypeResolver;
        }
        return $this->userDeleteMutationPayloadObjectTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            UserObjectTypeResolver::class,
        ];
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            'update',
            'delete',
        ];
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'update' => $this->__('Update the user', 'gatographql'),
            'delete' => $this->__('Delete the user', 'gatographql'),
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
                'update' => SchemaTypeModifiers::NONE,
                'delete' => SchemaTypeModifiers::NON_NULLABLE,
                default => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
            };
        }
        return match ($fieldName) {
            'update',
            'delete' => SchemaTypeModifiers::NON_NULLABLE,
            default => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            'update' => [
                'input' => $this->getUserUpdateInputObjectTypeResolver(),
            ],
            'delete' => [
                'input' => $this->getUserDeleteInputObjectTypeResolver(),
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
     * present in the field argument in the query
     */
    public function validateMutationOnObject(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): bool
    {
        return match ($fieldName) {
            'update',
            'delete' => true,
            default => parent::validateMutationOnObject($objectTypeResolver, $fieldName),
        };
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
            case 'update':
                /** @var stdClass */
                $input = &$fieldArgsForMutationForObject['input'];
                $input->{MutationInputProperties::ID} = $objectTypeResolver->getID($user);
                break;
            case 'delete':
                /**
                 * The "input" is optional, as it only carries the
                 * `reassignAuthorContentTo` input field. Hence create it
                 * if it was not provided.
                 */
                if (!isset($fieldArgsForMutationForObject['input'])) {
                    $fieldArgsForMutationForObject['input'] = new stdClass();
                }
                $fieldArgsForMutationForObject['input']->{MutationInputProperties::ID} = $objectTypeResolver->getID($user);
                break;
        }
        return $fieldArgsForMutationForObject;
    }

    public function getFieldMutationResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?MutationResolverInterface
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableUserMutations = $moduleConfiguration->usePayloadableUserMutations();
        return match ($fieldName) {
            'update' => $usePayloadableUserMutations
                ? $this->getPayloadableUpdateUserMutationResolver()
                : $this->getUpdateUserMutationResolver(),
            'delete' => $usePayloadableUserMutations
                ? $this->getPayloadableDeleteUserMutationResolver()
                : $this->getDeleteUserMutationResolver(),
            default => parent::getFieldMutationResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableUserMutations = $moduleConfiguration->usePayloadableUserMutations();
        return match ($fieldName) {
            'update' => $usePayloadableUserMutations
                ? $this->getUserUpdateMutationPayloadObjectTypeResolver()
                : $this->getUserObjectTypeResolver(),
            'delete' => $usePayloadableUserMutations
                ? $this->getUserDeleteMutationPayloadObjectTypeResolver()
                : $this->getBooleanScalarTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
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

        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableUserMutations = $moduleConfiguration->usePayloadableUserMutations();
        if ($usePayloadableUserMutations) {
            return $validationCheckpoints;
        }

        switch ($fieldDataAccessor->getFieldName()) {
            case 'update':
            case 'delete':
                $validationCheckpoints[] = $this->getUserLoggedInCheckpoint();
                break;
        }
        return $validationCheckpoints;
    }
}
