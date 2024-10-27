<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\MediaMutations\Module;
use PoPCMSSchema\MediaMutations\ModuleConfiguration;
use PoPCMSSchema\MediaMutations\MutationResolvers\CreateMediaItemBulkOperationMutationResolver;
use PoPCMSSchema\MediaMutations\MutationResolvers\CreateMediaItemMutationResolver;
use PoPCMSSchema\MediaMutations\MutationResolvers\PayloadableCreateMediaItemBulkOperationMutationResolver;
use PoPCMSSchema\MediaMutations\MutationResolvers\PayloadableCreateMediaItemMutationResolver;
use PoPCMSSchema\MediaMutations\MutationResolvers\PayloadableUpdateMediaItemBulkOperationMutationResolver;
use PoPCMSSchema\MediaMutations\MutationResolvers\PayloadableUpdateMediaItemMutationResolver;
use PoPCMSSchema\MediaMutations\MutationResolvers\UpdateMediaItemBulkOperationMutationResolver;
use PoPCMSSchema\MediaMutations\MutationResolvers\UpdateMediaItemMutationResolver;
use PoPCMSSchema\MediaMutations\TypeResolvers\InputObjectType\RootCreateMediaItemInputObjectTypeResolver;
use PoPCMSSchema\MediaMutations\TypeResolvers\InputObjectType\RootUpdateMediaItemInputObjectTypeResolver;
use PoPCMSSchema\MediaMutations\TypeResolvers\ObjectType\RootCreateMediaItemMutationPayloadObjectTypeResolver;
use PoPCMSSchema\MediaMutations\TypeResolvers\ObjectType\RootUpdateMediaItemMutationPayloadObjectTypeResolver;
use PoPCMSSchema\Media\TypeResolvers\ObjectType\MediaObjectTypeResolver;
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
use PoP\Engine\Module as EngineModule;
use PoP\Engine\ModuleConfiguration as EngineModuleConfiguration;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\Root\App;

class RootObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    use MutationPayloadObjectsObjectTypeFieldResolverTrait;
    use BulkOperationDecoratorObjectTypeFieldResolverTrait;

    private ?MediaObjectTypeResolver $mediaObjectTypeResolver = null;
    private ?CreateMediaItemMutationResolver $createMediaItemMutationResolver = null;
    private ?CreateMediaItemBulkOperationMutationResolver $createMediaItemBulkOperationMutationResolver = null;
    private ?UpdateMediaItemMutationResolver $updateMediaItemMutationResolver = null;
    private ?UpdateMediaItemBulkOperationMutationResolver $updateMediaItemBulkOperationMutationResolver = null;
    private ?RootCreateMediaItemInputObjectTypeResolver $rootCreateMediaItemInputObjectTypeResolver = null;
    private ?RootCreateMediaItemMutationPayloadObjectTypeResolver $rootCreateMediaItemMutationPayloadObjectTypeResolver = null;
    private ?PayloadableCreateMediaItemMutationResolver $payloadableCreateMediaItemMutationResolver = null;
    private ?PayloadableCreateMediaItemBulkOperationMutationResolver $payloadableCreateMediaItemBulkOperationMutationResolver = null;
    private ?RootUpdateMediaItemInputObjectTypeResolver $rootUpdateMediaItemInputObjectTypeResolver = null;
    private ?RootUpdateMediaItemMutationPayloadObjectTypeResolver $rootUpdateMediaItemMutationPayloadObjectTypeResolver = null;
    private ?PayloadableUpdateMediaItemMutationResolver $payloadableUpdateMediaItemMutationResolver = null;
    private ?PayloadableUpdateMediaItemBulkOperationMutationResolver $payloadableUpdateMediaItemBulkOperationMutationResolver = null;
    private ?UserLoggedInCheckpoint $userLoggedInCheckpoint = null;

    final protected function getMediaObjectTypeResolver(): MediaObjectTypeResolver
    {
        if ($this->mediaObjectTypeResolver === null) {
            /** @var MediaObjectTypeResolver */
            $mediaObjectTypeResolver = $this->instanceManager->getInstance(MediaObjectTypeResolver::class);
            $this->mediaObjectTypeResolver = $mediaObjectTypeResolver;
        }
        return $this->mediaObjectTypeResolver;
    }
    final protected function getCreateMediaItemMutationResolver(): CreateMediaItemMutationResolver
    {
        if ($this->createMediaItemMutationResolver === null) {
            /** @var CreateMediaItemMutationResolver */
            $createMediaItemMutationResolver = $this->instanceManager->getInstance(CreateMediaItemMutationResolver::class);
            $this->createMediaItemMutationResolver = $createMediaItemMutationResolver;
        }
        return $this->createMediaItemMutationResolver;
    }
    final protected function getCreateMediaItemBulkOperationMutationResolver(): CreateMediaItemBulkOperationMutationResolver
    {
        if ($this->createMediaItemBulkOperationMutationResolver === null) {
            /** @var CreateMediaItemBulkOperationMutationResolver */
            $createMediaItemBulkOperationMutationResolver = $this->instanceManager->getInstance(CreateMediaItemBulkOperationMutationResolver::class);
            $this->createMediaItemBulkOperationMutationResolver = $createMediaItemBulkOperationMutationResolver;
        }
        return $this->createMediaItemBulkOperationMutationResolver;
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
    final protected function getUpdateMediaItemBulkOperationMutationResolver(): UpdateMediaItemBulkOperationMutationResolver
    {
        if ($this->updateMediaItemBulkOperationMutationResolver === null) {
            /** @var UpdateMediaItemBulkOperationMutationResolver */
            $updateMediaItemBulkOperationMutationResolver = $this->instanceManager->getInstance(UpdateMediaItemBulkOperationMutationResolver::class);
            $this->updateMediaItemBulkOperationMutationResolver = $updateMediaItemBulkOperationMutationResolver;
        }
        return $this->updateMediaItemBulkOperationMutationResolver;
    }
    final protected function getRootCreateMediaItemInputObjectTypeResolver(): RootCreateMediaItemInputObjectTypeResolver
    {
        if ($this->rootCreateMediaItemInputObjectTypeResolver === null) {
            /** @var RootCreateMediaItemInputObjectTypeResolver */
            $rootCreateMediaItemInputObjectTypeResolver = $this->instanceManager->getInstance(RootCreateMediaItemInputObjectTypeResolver::class);
            $this->rootCreateMediaItemInputObjectTypeResolver = $rootCreateMediaItemInputObjectTypeResolver;
        }
        return $this->rootCreateMediaItemInputObjectTypeResolver;
    }
    final protected function getRootCreateMediaItemMutationPayloadObjectTypeResolver(): RootCreateMediaItemMutationPayloadObjectTypeResolver
    {
        if ($this->rootCreateMediaItemMutationPayloadObjectTypeResolver === null) {
            /** @var RootCreateMediaItemMutationPayloadObjectTypeResolver */
            $rootCreateMediaItemMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootCreateMediaItemMutationPayloadObjectTypeResolver::class);
            $this->rootCreateMediaItemMutationPayloadObjectTypeResolver = $rootCreateMediaItemMutationPayloadObjectTypeResolver;
        }
        return $this->rootCreateMediaItemMutationPayloadObjectTypeResolver;
    }
    final protected function getPayloadableCreateMediaItemMutationResolver(): PayloadableCreateMediaItemMutationResolver
    {
        if ($this->payloadableCreateMediaItemMutationResolver === null) {
            /** @var PayloadableCreateMediaItemMutationResolver */
            $payloadableCreateMediaItemMutationResolver = $this->instanceManager->getInstance(PayloadableCreateMediaItemMutationResolver::class);
            $this->payloadableCreateMediaItemMutationResolver = $payloadableCreateMediaItemMutationResolver;
        }
        return $this->payloadableCreateMediaItemMutationResolver;
    }
    final protected function getPayloadableCreateMediaItemBulkOperationMutationResolver(): PayloadableCreateMediaItemBulkOperationMutationResolver
    {
        if ($this->payloadableCreateMediaItemBulkOperationMutationResolver === null) {
            /** @var PayloadableCreateMediaItemBulkOperationMutationResolver */
            $payloadableCreateMediaItemBulkOperationMutationResolver = $this->instanceManager->getInstance(PayloadableCreateMediaItemBulkOperationMutationResolver::class);
            $this->payloadableCreateMediaItemBulkOperationMutationResolver = $payloadableCreateMediaItemBulkOperationMutationResolver;
        }
        return $this->payloadableCreateMediaItemBulkOperationMutationResolver;
    }
    final protected function getRootUpdateMediaItemInputObjectTypeResolver(): RootUpdateMediaItemInputObjectTypeResolver
    {
        if ($this->rootUpdateMediaItemInputObjectTypeResolver === null) {
            /** @var RootUpdateMediaItemInputObjectTypeResolver */
            $rootUpdateMediaItemInputObjectTypeResolver = $this->instanceManager->getInstance(RootUpdateMediaItemInputObjectTypeResolver::class);
            $this->rootUpdateMediaItemInputObjectTypeResolver = $rootUpdateMediaItemInputObjectTypeResolver;
        }
        return $this->rootUpdateMediaItemInputObjectTypeResolver;
    }
    final protected function getRootUpdateMediaItemMutationPayloadObjectTypeResolver(): RootUpdateMediaItemMutationPayloadObjectTypeResolver
    {
        if ($this->rootUpdateMediaItemMutationPayloadObjectTypeResolver === null) {
            /** @var RootUpdateMediaItemMutationPayloadObjectTypeResolver */
            $rootUpdateMediaItemMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootUpdateMediaItemMutationPayloadObjectTypeResolver::class);
            $this->rootUpdateMediaItemMutationPayloadObjectTypeResolver = $rootUpdateMediaItemMutationPayloadObjectTypeResolver;
        }
        return $this->rootUpdateMediaItemMutationPayloadObjectTypeResolver;
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
    final protected function getPayloadableUpdateMediaItemBulkOperationMutationResolver(): PayloadableUpdateMediaItemBulkOperationMutationResolver
    {
        if ($this->payloadableUpdateMediaItemBulkOperationMutationResolver === null) {
            /** @var PayloadableUpdateMediaItemBulkOperationMutationResolver */
            $payloadableUpdateMediaItemBulkOperationMutationResolver = $this->instanceManager->getInstance(PayloadableUpdateMediaItemBulkOperationMutationResolver::class);
            $this->payloadableUpdateMediaItemBulkOperationMutationResolver = $payloadableUpdateMediaItemBulkOperationMutationResolver;
        }
        return $this->payloadableUpdateMediaItemBulkOperationMutationResolver;
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
        $addFieldsToQueryPayloadableMediaMutations = $moduleConfiguration->addFieldsToQueryPayloadableMediaMutations();
        return array_merge(
            [
                'createMediaItem',
                'createMediaItems',
            ],
            !$disableRedundantRootTypeMutationFields ? [
                'updateMediaItem',
                'updateMediaItems',
            ] : [],
            $addFieldsToQueryPayloadableMediaMutations ? [
                'createMediaItemMutationPayloadObjects',
            ] : [],
            $addFieldsToQueryPayloadableMediaMutations && !$disableRedundantRootTypeMutationFields ? [
                'updateMediaItemMutationPayloadObjects',
            ] : [],
        );
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'createMediaItem' => $this->__('Upload an attachment', 'media-mutations'),
            'createMediaItems' => $this->__('Upload attachments', 'media-mutations'),
            'updateMediaItem' => $this->__('Update the metadata for an attachment', 'media-mutations'),
            'updateMediaItems' => $this->__('Update the metadata for attachments', 'media-mutations'),
            'createMediaItemMutationPayloadObjects' => $this->__('Retrieve the payload objects from a recently-executed `createMediaItem` mutation', 'media-mutations'),
            'updateMediaItemMutationPayloadObjects' => $this->__('Retrieve the payload objects from a recently-executed `updateMediaItem` mutation', 'media-mutations'),
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
                'createMediaItem',
                'updateMediaItem'
                    => SchemaTypeModifiers::NONE,
                'createMediaItems',
                'updateMediaItems'
                    => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
                default
                    => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
            };
        }

        if (
            in_array($fieldName, [
            'createMediaItemMutationPayloadObjects',
            'updateMediaItemMutationPayloadObjects',
            ])
        ) {
            return $this->getMutationPayloadObjectsFieldTypeModifiers();
        }

        return match ($fieldName) {
            'createMediaItem',
            'updateMediaItem'
                => SchemaTypeModifiers::NON_NULLABLE,
            'createMediaItems',
            'updateMediaItems'
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
            'createMediaItem' => [
                'input' => $this->getRootCreateMediaItemInputObjectTypeResolver(),
            ],
            'createMediaItems' => $this->getBulkOperationFieldArgNameTypeResolvers($this->getRootCreateMediaItemInputObjectTypeResolver()),
            'updateMediaItem' => [
                'input' => $this->getRootUpdateMediaItemInputObjectTypeResolver(),
            ],
            'updateMediaItems' => $this->getBulkOperationFieldArgNameTypeResolvers($this->getRootUpdateMediaItemInputObjectTypeResolver()),
            'createMediaItemMutationPayloadObjects',
            'updateMediaItemMutationPayloadObjects'
                => $this->getMutationPayloadObjectsFieldArgNameTypeResolvers(),
            default
                => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        if (
            in_array($fieldName, [
            'createMediaItemMutationPayloadObjects',
            'updateMediaItemMutationPayloadObjects',
            ])
        ) {
            return $this->getMutationPayloadObjectsFieldArgTypeModifiers($fieldArgName)
                ?? parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
        }

        if (
            in_array($fieldName, [
            'createMediaItems',
            'updateMediaItems',
            ])
        ) {
            return $this->getBulkOperationFieldArgTypeModifiers($fieldArgName)
                ?? parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
        }

        return match ([$fieldName => $fieldArgName]) {
            ['createMediaItem' => 'input'],
            ['updateMediaItem' => 'input']
                => SchemaTypeModifiers::MANDATORY,
            default => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldArgDefaultValue(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): mixed
    {
        if (
            in_array($fieldName, [
            'createMediaItems',
            'updateMediaItems',
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
        $usePayloadableMediaMutations = $moduleConfiguration->usePayloadableMediaMutations();
        return match ($fieldName) {
            'createMediaItem' => $usePayloadableMediaMutations
                ? $this->getPayloadableCreateMediaItemMutationResolver()
                : $this->getCreateMediaItemMutationResolver(),
            'createMediaItems' => $usePayloadableMediaMutations
                ? $this->getPayloadableCreateMediaItemBulkOperationMutationResolver()
                : $this->getCreateMediaItemBulkOperationMutationResolver(),
            'updateMediaItem' => $usePayloadableMediaMutations
                ? $this->getPayloadableUpdateMediaItemMutationResolver()
                : $this->getUpdateMediaItemMutationResolver(),
            'updateMediaItems' => $usePayloadableMediaMutations
                ? $this->getPayloadableUpdateMediaItemBulkOperationMutationResolver()
                : $this->getUpdateMediaItemBulkOperationMutationResolver(),
            default => parent::getFieldMutationResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableMediaMutations = $moduleConfiguration->usePayloadableMediaMutations();
        if ($usePayloadableMediaMutations) {
            return match ($fieldName) {
                'createMediaItem',
                'createMediaItems',
                'createMediaItemMutationPayloadObjects'
                    => $this->getRootCreateMediaItemMutationPayloadObjectTypeResolver(),
                'updateMediaItem',
                'updateMediaItems',
                'updateMediaItemMutationPayloadObjects'
                    => $this->getRootUpdateMediaItemMutationPayloadObjectTypeResolver(),
                default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
            };
        }
        return match ($fieldName) {
            'createMediaItem',
            'createMediaItems',
            'updateMediaItem',
            'updateMediaItems'
                => $this->getMediaObjectTypeResolver(),
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
        $usePayloadableMediaMutations = $moduleConfiguration->usePayloadableMediaMutations();
        if ($usePayloadableMediaMutations) {
            return $validationCheckpoints;
        }

        switch ($fieldDataAccessor->getFieldName()) {
            case 'createMediaItem':
            case 'createMediaItems':
            case 'updateMediaItem':
            case 'updateMediaItems':
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
            case 'createMediaItemMutationPayloadObjects':
            case 'updateMediaItemMutationPayloadObjects':
                return $this->resolveMutationPayloadObjectsValue(
                    $objectTypeResolver,
                    $fieldDataAccessor,
                );
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
