<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\MediaMutations\Constants\MutationInputProperties;
use PoPCMSSchema\MediaMutations\Module as MediaMutationsModule;
use PoPCMSSchema\MediaMutations\Module;
use PoPCMSSchema\MediaMutations\ModuleConfiguration as MediaMutationsModuleConfiguration;
use PoPCMSSchema\MediaMutations\ModuleConfiguration;
use PoPCMSSchema\MediaMutations\MutationResolvers\PayloadableUpdateMediaItemMutationResolver;
use PoPCMSSchema\MediaMutations\MutationResolvers\UpdateMediaItemMutationResolver;
use PoPCMSSchema\MediaMutations\TypeResolvers\InputObjectType\MediaUpdateInputObjectTypeResolver;
use PoPCMSSchema\MediaMutations\TypeResolvers\ObjectType\MediaUpdateMutationPayloadObjectTypeResolver;
use PoPCMSSchema\Media\TypeResolvers\ObjectType\MediaObjectTypeResolver;
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
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use stdClass;

class MediaObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?MediaObjectTypeResolver $mediaObjectTypeResolver = null;
    private ?MediaUpdateMutationPayloadObjectTypeResolver $mediaUpdateMutationPayloadObjectTypeResolver = null;
    private ?UpdateMediaItemMutationResolver $updateMediaItemMutationResolver = null;
    private ?PayloadableUpdateMediaItemMutationResolver $payloadableUpdateMediaItemMutationResolver = null;
    private ?MediaUpdateInputObjectTypeResolver $mediaUpdateInputObjectTypeResolver = null;
    private ?UserLoggedInCheckpoint $userLoggedInCheckpoint = null;

    final public function setMediaObjectTypeResolver(MediaObjectTypeResolver $mediaObjectTypeResolver): void
    {
        $this->mediaObjectTypeResolver = $mediaObjectTypeResolver;
    }
    final protected function getMediaObjectTypeResolver(): MediaObjectTypeResolver
    {
        if ($this->mediaObjectTypeResolver === null) {
            /** @var MediaObjectTypeResolver */
            $mediaObjectTypeResolver = $this->instanceManager->getInstance(MediaObjectTypeResolver::class);
            $this->mediaObjectTypeResolver = $mediaObjectTypeResolver;
        }
        return $this->mediaObjectTypeResolver;
    }
    final public function setMediaUpdateMutationPayloadObjectTypeResolver(MediaUpdateMutationPayloadObjectTypeResolver $mediaUpdateMutationPayloadObjectTypeResolver): void
    {
        $this->mediaUpdateMutationPayloadObjectTypeResolver = $mediaUpdateMutationPayloadObjectTypeResolver;
    }
    final protected function getMediaUpdateMutationPayloadObjectTypeResolver(): MediaUpdateMutationPayloadObjectTypeResolver
    {
        if ($this->mediaUpdateMutationPayloadObjectTypeResolver === null) {
            /** @var MediaUpdateMutationPayloadObjectTypeResolver */
            $mediaUpdateMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(MediaUpdateMutationPayloadObjectTypeResolver::class);
            $this->mediaUpdateMutationPayloadObjectTypeResolver = $mediaUpdateMutationPayloadObjectTypeResolver;
        }
        return $this->mediaUpdateMutationPayloadObjectTypeResolver;
    }
    final public function setUpdateMediaItemMutationResolver(UpdateMediaItemMutationResolver $updateMediaItemMutationResolver): void
    {
        $this->updateMediaItemMutationResolver = $updateMediaItemMutationResolver;
    }
    final protected function getUpdateMediaItemMutationResolver(): UpdateMediaItemMutationResolver
    {
        if ($this->updateMediaItemMutationResolver === null) {
            /** @var UpdateMediaItemMutationResolver */
            $updateMediaItemMutationResolver = $this->instanceManager->getInstance(UpdateMediaItemMutationResolver::class);
            $this->updateMediaItemMutationResolver = $updateMediaItemMutationResolver;
        }
        return $this->updateMediaItemMutationResolver;
    }
    final public function setPayloadableUpdateMediaItemMutationResolver(PayloadableUpdateMediaItemMutationResolver $payloadableUpdateMediaItemMutationResolver): void
    {
        $this->payloadableUpdateMediaItemMutationResolver = $payloadableUpdateMediaItemMutationResolver;
    }
    final protected function getPayloadableUpdateMediaItemMutationResolver(): PayloadableUpdateMediaItemMutationResolver
    {
        if ($this->payloadableUpdateMediaItemMutationResolver === null) {
            /** @var PayloadableUpdateMediaItemMutationResolver */
            $payloadableUpdateMediaItemMutationResolver = $this->instanceManager->getInstance(PayloadableUpdateMediaItemMutationResolver::class);
            $this->payloadableUpdateMediaItemMutationResolver = $payloadableUpdateMediaItemMutationResolver;
        }
        return $this->payloadableUpdateMediaItemMutationResolver;
    }
    final public function setMediaUpdateInputObjectTypeResolver(MediaUpdateInputObjectTypeResolver $mediaUpdateInputObjectTypeResolver): void
    {
        $this->mediaUpdateInputObjectTypeResolver = $mediaUpdateInputObjectTypeResolver;
    }
    final protected function getMediaUpdateInputObjectTypeResolver(): MediaUpdateInputObjectTypeResolver
    {
        if ($this->mediaUpdateInputObjectTypeResolver === null) {
            /** @var MediaUpdateInputObjectTypeResolver */
            $mediaUpdateInputObjectTypeResolver = $this->instanceManager->getInstance(MediaUpdateInputObjectTypeResolver::class);
            $this->mediaUpdateInputObjectTypeResolver = $mediaUpdateInputObjectTypeResolver;
        }
        return $this->mediaUpdateInputObjectTypeResolver;
    }
    final public function setUserLoggedInCheckpoint(UserLoggedInCheckpoint $userLoggedInCheckpoint): void
    {
        $this->userLoggedInCheckpoint = $userLoggedInCheckpoint;
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
            MediaObjectTypeResolver::class,
        ];
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            'update',
        ];
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'update' => $this->__('Update the media item', 'media-mutations'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableMediaMutations = $moduleConfiguration->usePayloadableMediaMutations();
        if (!$usePayloadableMediaMutations) {
            return match ($fieldName) {
                'update' => SchemaTypeModifiers::NONE,
                default => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
            };
        }
        return match ($fieldName) {
            'update' => SchemaTypeModifiers::NON_NULLABLE,
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
                'input' => $this->getMediaUpdateInputObjectTypeResolver(),
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
            'update' => true,
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
        $mediaItem = $object;
        switch ($field->getName()) {
            case 'update':
                /** @var stdClass */
                $input = &$fieldArgsForMutationForObject['input'];
                $input->{MutationInputProperties::ID} = $objectTypeResolver->getID($mediaItem);
                break;
        }
        return $fieldArgsForMutationForObject;
    }

    public function getFieldMutationResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?MutationResolverInterface
    {
        /** @var MediaMutationsModuleConfiguration */
        $moduleConfiguration = App::getModule(MediaMutationsModule::class)->getConfiguration();
        $usePayloadableMediaMutations = $moduleConfiguration->usePayloadableMediaMutations();
        return match ($fieldName) {
            'update' => $usePayloadableMediaMutations
                ? $this->getPayloadableUpdateMediaItemMutationResolver()
                : $this->getUpdateMediaItemMutationResolver(),
            default => parent::getFieldMutationResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        /** @var MediaMutationsModuleConfiguration */
        $moduleConfiguration = App::getModule(MediaMutationsModule::class)->getConfiguration();
        $usePayloadableMediaMutations = $moduleConfiguration->usePayloadableMediaMutations();
        return match ($fieldName) {
            'update' => $usePayloadableMediaMutations
                ? $this->getMediaUpdateMutationPayloadObjectTypeResolver()
                : $this->getMediaObjectTypeResolver(),
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

        /**
         * For Payloadable: The "User Logged-in" checkpoint validation is not added,
         * instead this validation is executed inside the mutation, so the error
         * shows up in the Payload
         *
         * @var ModuleConfiguration
         */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableMediaMutations = $moduleConfiguration->usePayloadableMediaMutations();
        if ($usePayloadableMediaMutations) {
            return $validationCheckpoints;
        }

        switch ($fieldDataAccessor->getFieldName()) {
            case 'update':
                $validationCheckpoints[] = $this->getUserLoggedInCheckpoint();
                break;
        }
        return $validationCheckpoints;
    }
}
