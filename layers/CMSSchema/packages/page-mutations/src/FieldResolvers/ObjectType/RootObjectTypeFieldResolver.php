<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostMutations\Module as CustomPostMutationsModule;
use PoPCMSSchema\CustomPostMutations\ModuleConfiguration as CustomPostMutationsModuleConfiguration;
use PoPCMSSchema\PageMutations\MutationResolvers\CreatePageMutationResolver;
use PoPCMSSchema\PageMutations\MutationResolvers\PayloadableCreatePageMutationResolver;
use PoPCMSSchema\PageMutations\MutationResolvers\PayloadableUpdatePageMutationResolver;
use PoPCMSSchema\PageMutations\MutationResolvers\UpdatePageMutationResolver;
use PoPCMSSchema\PageMutations\TypeResolvers\InputObjectType\RootCreatePageInputObjectTypeResolver;
use PoPCMSSchema\PageMutations\TypeResolvers\InputObjectType\RootUpdatePageInputObjectTypeResolver;
use PoPCMSSchema\PageMutations\TypeResolvers\ObjectType\RootCreatePageMutationPayloadObjectTypeResolver;
use PoPCMSSchema\PageMutations\TypeResolvers\ObjectType\RootUpdatePageMutationPayloadObjectTypeResolver;
use PoPCMSSchema\Pages\TypeResolvers\ObjectType\PageObjectTypeResolver;
use PoPCMSSchema\SchemaCommons\Constants\MutationInputProperties as SchemaCommonsMutationInputProperties;
use PoPCMSSchema\SchemaCommons\FieldResolvers\ObjectType\MutationPayloadObjectsObjectTypeFieldResolverTrait;
use PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType\MutationPayloadObjectsInputObjectTypeResolver;
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

    private ?PageObjectTypeResolver $pageObjectTypeResolver = null;
    private ?RootUpdatePageMutationPayloadObjectTypeResolver $rootUpdatePageMutationPayloadObjectTypeResolver = null;
    private ?RootCreatePageMutationPayloadObjectTypeResolver $rootCreatePageMutationPayloadObjectTypeResolver = null;
    private ?CreatePageMutationResolver $createPageMutationResolver = null;
    private ?UpdatePageMutationResolver $updatePageMutationResolver = null;
    private ?PayloadableUpdatePageMutationResolver $payloadableUpdatePageMutationResolver = null;
    private ?PayloadableCreatePageMutationResolver $payloadableCreatePageMutationResolver = null;
    private ?RootUpdatePageInputObjectTypeResolver $rootUpdatePageInputObjectTypeResolver = null;
    private ?RootCreatePageInputObjectTypeResolver $rootCreatePageInputObjectTypeResolver = null;
    private ?UserLoggedInCheckpoint $userLoggedInCheckpoint = null;
    private ?MutationPayloadObjectsInputObjectTypeResolver $mutationPayloadObjectsInputObjectTypeResolver = null;

    final public function setPageObjectTypeResolver(PageObjectTypeResolver $pageObjectTypeResolver): void
    {
        $this->pageObjectTypeResolver = $pageObjectTypeResolver;
    }
    final protected function getPageObjectTypeResolver(): PageObjectTypeResolver
    {
        if ($this->pageObjectTypeResolver === null) {
            /** @var PageObjectTypeResolver */
            $pageObjectTypeResolver = $this->instanceManager->getInstance(PageObjectTypeResolver::class);
            $this->pageObjectTypeResolver = $pageObjectTypeResolver;
        }
        return $this->pageObjectTypeResolver;
    }
    final public function setRootUpdatePageMutationPayloadObjectTypeResolver(RootUpdatePageMutationPayloadObjectTypeResolver $rootUpdatePageMutationPayloadObjectTypeResolver): void
    {
        $this->rootUpdatePageMutationPayloadObjectTypeResolver = $rootUpdatePageMutationPayloadObjectTypeResolver;
    }
    final protected function getRootUpdatePageMutationPayloadObjectTypeResolver(): RootUpdatePageMutationPayloadObjectTypeResolver
    {
        if ($this->rootUpdatePageMutationPayloadObjectTypeResolver === null) {
            /** @var RootUpdatePageMutationPayloadObjectTypeResolver */
            $rootUpdatePageMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootUpdatePageMutationPayloadObjectTypeResolver::class);
            $this->rootUpdatePageMutationPayloadObjectTypeResolver = $rootUpdatePageMutationPayloadObjectTypeResolver;
        }
        return $this->rootUpdatePageMutationPayloadObjectTypeResolver;
    }
    final public function setRootCreatePageMutationPayloadObjectTypeResolver(RootCreatePageMutationPayloadObjectTypeResolver $rootCreatePageMutationPayloadObjectTypeResolver): void
    {
        $this->rootCreatePageMutationPayloadObjectTypeResolver = $rootCreatePageMutationPayloadObjectTypeResolver;
    }
    final protected function getRootCreatePageMutationPayloadObjectTypeResolver(): RootCreatePageMutationPayloadObjectTypeResolver
    {
        if ($this->rootCreatePageMutationPayloadObjectTypeResolver === null) {
            /** @var RootCreatePageMutationPayloadObjectTypeResolver */
            $rootCreatePageMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootCreatePageMutationPayloadObjectTypeResolver::class);
            $this->rootCreatePageMutationPayloadObjectTypeResolver = $rootCreatePageMutationPayloadObjectTypeResolver;
        }
        return $this->rootCreatePageMutationPayloadObjectTypeResolver;
    }
    final public function setCreatePageMutationResolver(CreatePageMutationResolver $createPageMutationResolver): void
    {
        $this->createPageMutationResolver = $createPageMutationResolver;
    }
    final protected function getCreatePageMutationResolver(): CreatePageMutationResolver
    {
        if ($this->createPageMutationResolver === null) {
            /** @var CreatePageMutationResolver */
            $createPageMutationResolver = $this->instanceManager->getInstance(CreatePageMutationResolver::class);
            $this->createPageMutationResolver = $createPageMutationResolver;
        }
        return $this->createPageMutationResolver;
    }
    final public function setUpdatePageMutationResolver(UpdatePageMutationResolver $updatePageMutationResolver): void
    {
        $this->updatePageMutationResolver = $updatePageMutationResolver;
    }
    final protected function getUpdatePageMutationResolver(): UpdatePageMutationResolver
    {
        if ($this->updatePageMutationResolver === null) {
            /** @var UpdatePageMutationResolver */
            $updatePageMutationResolver = $this->instanceManager->getInstance(UpdatePageMutationResolver::class);
            $this->updatePageMutationResolver = $updatePageMutationResolver;
        }
        return $this->updatePageMutationResolver;
    }
    final public function setPayloadableUpdatePageMutationResolver(PayloadableUpdatePageMutationResolver $payloadableUpdatePageMutationResolver): void
    {
        $this->payloadableUpdatePageMutationResolver = $payloadableUpdatePageMutationResolver;
    }
    final protected function getPayloadableUpdatePageMutationResolver(): PayloadableUpdatePageMutationResolver
    {
        if ($this->payloadableUpdatePageMutationResolver === null) {
            /** @var PayloadableUpdatePageMutationResolver */
            $payloadableUpdatePageMutationResolver = $this->instanceManager->getInstance(PayloadableUpdatePageMutationResolver::class);
            $this->payloadableUpdatePageMutationResolver = $payloadableUpdatePageMutationResolver;
        }
        return $this->payloadableUpdatePageMutationResolver;
    }
    final public function setPayloadableCreatePageMutationResolver(PayloadableCreatePageMutationResolver $payloadableCreatePageMutationResolver): void
    {
        $this->payloadableCreatePageMutationResolver = $payloadableCreatePageMutationResolver;
    }
    final protected function getPayloadableCreatePageMutationResolver(): PayloadableCreatePageMutationResolver
    {
        if ($this->payloadableCreatePageMutationResolver === null) {
            /** @var PayloadableCreatePageMutationResolver */
            $payloadableCreatePageMutationResolver = $this->instanceManager->getInstance(PayloadableCreatePageMutationResolver::class);
            $this->payloadableCreatePageMutationResolver = $payloadableCreatePageMutationResolver;
        }
        return $this->payloadableCreatePageMutationResolver;
    }
    final public function setRootUpdatePageInputObjectTypeResolver(RootUpdatePageInputObjectTypeResolver $rootUpdatePageInputObjectTypeResolver): void
    {
        $this->rootUpdatePageInputObjectTypeResolver = $rootUpdatePageInputObjectTypeResolver;
    }
    final protected function getRootUpdatePageInputObjectTypeResolver(): RootUpdatePageInputObjectTypeResolver
    {
        if ($this->rootUpdatePageInputObjectTypeResolver === null) {
            /** @var RootUpdatePageInputObjectTypeResolver */
            $rootUpdatePageInputObjectTypeResolver = $this->instanceManager->getInstance(RootUpdatePageInputObjectTypeResolver::class);
            $this->rootUpdatePageInputObjectTypeResolver = $rootUpdatePageInputObjectTypeResolver;
        }
        return $this->rootUpdatePageInputObjectTypeResolver;
    }
    final public function setRootCreatePageInputObjectTypeResolver(RootCreatePageInputObjectTypeResolver $rootCreatePageInputObjectTypeResolver): void
    {
        $this->rootCreatePageInputObjectTypeResolver = $rootCreatePageInputObjectTypeResolver;
    }
    final protected function getRootCreatePageInputObjectTypeResolver(): RootCreatePageInputObjectTypeResolver
    {
        if ($this->rootCreatePageInputObjectTypeResolver === null) {
            /** @var RootCreatePageInputObjectTypeResolver */
            $rootCreatePageInputObjectTypeResolver = $this->instanceManager->getInstance(RootCreatePageInputObjectTypeResolver::class);
            $this->rootCreatePageInputObjectTypeResolver = $rootCreatePageInputObjectTypeResolver;
        }
        return $this->rootCreatePageInputObjectTypeResolver;
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
    final public function setMutationPayloadObjectsInputObjectTypeResolver(MutationPayloadObjectsInputObjectTypeResolver $mutationPayloadObjectsInputObjectTypeResolver): void
    {
        $this->mutationPayloadObjectsInputObjectTypeResolver = $mutationPayloadObjectsInputObjectTypeResolver;
    }
    final protected function getMutationPayloadObjectsInputObjectTypeResolver(): MutationPayloadObjectsInputObjectTypeResolver
    {
        if ($this->mutationPayloadObjectsInputObjectTypeResolver === null) {
            /** @var MutationPayloadObjectsInputObjectTypeResolver */
            $mutationPayloadObjectsInputObjectTypeResolver = $this->instanceManager->getInstance(MutationPayloadObjectsInputObjectTypeResolver::class);
            $this->mutationPayloadObjectsInputObjectTypeResolver = $mutationPayloadObjectsInputObjectTypeResolver;
        }
        return $this->mutationPayloadObjectsInputObjectTypeResolver;
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
        /** @var CustomPostMutationsModuleConfiguration */
        $customPostMutationsModuleConfiguration = App::getModule(CustomPostMutationsModule::class)->getConfiguration();
        $addFieldsToQueryPayloadableCustomPostMutations = $customPostMutationsModuleConfiguration->addFieldsToQueryPayloadableCustomPostMutations();
        return array_merge(
            [
                'createPage',
            ],
            !$disableRedundantRootTypeMutationFields ? [
                'updatePage',
            ] : [],
            $addFieldsToQueryPayloadableCustomPostMutations ? [
                'createPageMutationPayloadObjects',
            ] : [],
            $addFieldsToQueryPayloadableCustomPostMutations && !$disableRedundantRootTypeMutationFields ? [
                'updatePageMutationPayloadObjects',
            ] : [],
        );
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'createPage' => $this->__('Create a page', 'page-mutations'),
            'updatePage' => $this->__('Update a page', 'page-mutations'),
            'createPageMutationPayloadObjects' => $this->__('Retrieve the payload objects from a recently-executed `createPage` mutation', 'page-mutations'),
            'updatePageMutationPayloadObjects' => $this->__('Retrieve the payload objects from a recently-executed `updatePage` mutation', 'page-mutations'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        /** @var CustomPostMutationsModuleConfiguration */
        $moduleConfiguration = App::getModule(CustomPostMutationsModule::class)->getConfiguration();
        $usePayloadableCustomPostMutations = $moduleConfiguration->usePayloadableCustomPostMutations();
        if (!$usePayloadableCustomPostMutations) {
            return parent::getFieldTypeModifiers($objectTypeResolver, $fieldName);
        }
        return match ($fieldName) {
            'createPage',
            'updatePage'
                => SchemaTypeModifiers::NON_NULLABLE,
            'createPageMutationPayloadObjects',
            'updatePageMutationPayloadObjects'
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
            'createPage' => [
                'input' => $this->getRootCreatePageInputObjectTypeResolver(),
            ],
            'updatePage' => [
                'input' => $this->getRootUpdatePageInputObjectTypeResolver(),
            ],
            'createPageMutationPayloadObjects',
            'updatePageMutationPayloadObjects' => [
                SchemaCommonsMutationInputProperties::INPUT => $this->getMutationPayloadObjectsInputObjectTypeResolver(),
            ],
            default => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return match ([$fieldName => $fieldArgName]) {
            ['createPage' => 'input'],
            ['updatePage' => 'input'],
            ['createPageMutationPayloadObjects' => SchemaCommonsMutationInputProperties::INPUT],
            ['updatePageMutationPayloadObjects' => SchemaCommonsMutationInputProperties::INPUT]
                => SchemaTypeModifiers::MANDATORY,
            default => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldMutationResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?MutationResolverInterface
    {
        /** @var CustomPostMutationsModuleConfiguration */
        $moduleConfiguration = App::getModule(CustomPostMutationsModule::class)->getConfiguration();
        $usePayloadableCustomPostMutations = $moduleConfiguration->usePayloadableCustomPostMutations();
        return match ($fieldName) {
            'createPage' => $usePayloadableCustomPostMutations
                ? $this->getPayloadableCreatePageMutationResolver()
                : $this->getCreatePageMutationResolver(),
            'updatePage' => $usePayloadableCustomPostMutations
                ? $this->getPayloadableUpdatePageMutationResolver()
                : $this->getUpdatePageMutationResolver(),
            default => parent::getFieldMutationResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        /** @var CustomPostMutationsModuleConfiguration */
        $moduleConfiguration = App::getModule(CustomPostMutationsModule::class)->getConfiguration();
        $usePayloadableCustomPostMutations = $moduleConfiguration->usePayloadableCustomPostMutations();
        if ($usePayloadableCustomPostMutations) {
            return match ($fieldName) {
                'createPage',
                'createPageMutationPayloadObjects'
                    => $this->getRootCreatePageMutationPayloadObjectTypeResolver(),
                'updatePage',
                'updatePageMutationPayloadObjects'
                    => $this->getRootUpdatePageMutationPayloadObjectTypeResolver(),
                default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
            };
        }
        return match ($fieldName) {
            'createPage',
            'updatePage'
                => $this->getPageObjectTypeResolver(),
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
         * @var CustomPostMutationsModuleConfiguration
         */
        $moduleConfiguration = App::getModule(CustomPostMutationsModule::class)->getConfiguration();
        $usePayloadableCustomPostMutations = $moduleConfiguration->usePayloadableCustomPostMutations();
        if ($usePayloadableCustomPostMutations) {
            return $validationCheckpoints;
        }

        switch ($fieldDataAccessor->getFieldName()) {
            case 'createPage':
            case 'updatePage':
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
            case 'createPageMutationPayloadObjects':
            case 'updatePageMutationPayloadObjects':
                return $this->resolveMutationPayloadObjectsValue(
                    $objectTypeResolver,
                    $fieldDataAccessor,
                );
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
