<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostMutations\Module as CustomPostMutationsModule;
use PoPCMSSchema\CustomPostMutations\ModuleConfiguration as CustomPostMutationsModuleConfiguration;
use PoPCMSSchema\PageMutations\MutationResolvers\CreatePageBulkOperationMutationResolver;
use PoPCMSSchema\PageMutations\MutationResolvers\CreatePageMutationResolver;
use PoPCMSSchema\PageMutations\MutationResolvers\DeletePageBulkOperationMutationResolver;
use PoPCMSSchema\PageMutations\MutationResolvers\DeletePageMutationResolver;
use PoPCMSSchema\PageMutations\MutationResolvers\PayloadableCreatePageBulkOperationMutationResolver;
use PoPCMSSchema\PageMutations\MutationResolvers\PayloadableCreatePageMutationResolver;
use PoPCMSSchema\PageMutations\MutationResolvers\PayloadableDeletePageBulkOperationMutationResolver;
use PoPCMSSchema\PageMutations\MutationResolvers\PayloadableDeletePageMutationResolver;
use PoPCMSSchema\PageMutations\MutationResolvers\PayloadableUpdatePageBulkOperationMutationResolver;
use PoPCMSSchema\PageMutations\MutationResolvers\PayloadableUpdatePageMutationResolver;
use PoPCMSSchema\PageMutations\MutationResolvers\UpdatePageBulkOperationMutationResolver;
use PoPCMSSchema\PageMutations\MutationResolvers\UpdatePageMutationResolver;
use PoPCMSSchema\PageMutations\TypeResolvers\InputObjectType\RootCreatePageInputObjectTypeResolver;
use PoPCMSSchema\PageMutations\TypeResolvers\InputObjectType\RootDeletePageInputObjectTypeResolver;
use PoPCMSSchema\PageMutations\TypeResolvers\InputObjectType\RootUpdatePageInputObjectTypeResolver;
use PoPCMSSchema\PageMutations\TypeResolvers\ObjectType\RootCreatePageMutationPayloadObjectTypeResolver;
use PoPCMSSchema\PageMutations\TypeResolvers\ObjectType\RootDeletePageMutationPayloadObjectTypeResolver;
use PoPCMSSchema\PageMutations\TypeResolvers\ObjectType\RootUpdatePageMutationPayloadObjectTypeResolver;
use PoPCMSSchema\Pages\TypeResolvers\ObjectType\PageObjectTypeResolver;
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

    private ?PageObjectTypeResolver $pageObjectTypeResolver = null;
    private ?RootUpdatePageMutationPayloadObjectTypeResolver $rootUpdatePageMutationPayloadObjectTypeResolver = null;
    private ?RootCreatePageMutationPayloadObjectTypeResolver $rootCreatePageMutationPayloadObjectTypeResolver = null;
    private ?CreatePageMutationResolver $createPageMutationResolver = null;
    private ?CreatePageBulkOperationMutationResolver $createPageBulkOperationMutationResolver = null;
    private ?UpdatePageMutationResolver $updatePageMutationResolver = null;
    private ?UpdatePageBulkOperationMutationResolver $updatePageBulkOperationMutationResolver = null;
    private ?PayloadableUpdatePageMutationResolver $payloadableUpdatePageMutationResolver = null;
    private ?PayloadableUpdatePageBulkOperationMutationResolver $payloadableUpdatePageBulkOperationMutationResolver = null;
    private ?PayloadableCreatePageMutationResolver $payloadableCreatePageMutationResolver = null;
    private ?PayloadableCreatePageBulkOperationMutationResolver $payloadableCreatePageBulkOperationMutationResolver = null;
    private ?RootUpdatePageInputObjectTypeResolver $rootUpdatePageInputObjectTypeResolver = null;
    private ?RootCreatePageInputObjectTypeResolver $rootCreatePageInputObjectTypeResolver = null;
    private ?RootDeletePageMutationPayloadObjectTypeResolver $rootDeletePageMutationPayloadObjectTypeResolver = null;
    private ?DeletePageMutationResolver $deletePageMutationResolver = null;
    private ?DeletePageBulkOperationMutationResolver $deletePageBulkOperationMutationResolver = null;
    private ?PayloadableDeletePageMutationResolver $payloadableDeletePageMutationResolver = null;
    private ?PayloadableDeletePageBulkOperationMutationResolver $payloadableDeletePageBulkOperationMutationResolver = null;
    private ?RootDeletePageInputObjectTypeResolver $rootDeletePageInputObjectTypeResolver = null;
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;
    private ?UserLoggedInCheckpoint $userLoggedInCheckpoint = null;

    final protected function getPageObjectTypeResolver(): PageObjectTypeResolver
    {
        if ($this->pageObjectTypeResolver === null) {
            /** @var PageObjectTypeResolver */
            $pageObjectTypeResolver = $this->instanceManager->getInstance(PageObjectTypeResolver::class);
            $this->pageObjectTypeResolver = $pageObjectTypeResolver;
        }
        return $this->pageObjectTypeResolver;
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
    final protected function getRootCreatePageMutationPayloadObjectTypeResolver(): RootCreatePageMutationPayloadObjectTypeResolver
    {
        if ($this->rootCreatePageMutationPayloadObjectTypeResolver === null) {
            /** @var RootCreatePageMutationPayloadObjectTypeResolver */
            $rootCreatePageMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootCreatePageMutationPayloadObjectTypeResolver::class);
            $this->rootCreatePageMutationPayloadObjectTypeResolver = $rootCreatePageMutationPayloadObjectTypeResolver;
        }
        return $this->rootCreatePageMutationPayloadObjectTypeResolver;
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
    final protected function getCreatePageBulkOperationMutationResolver(): CreatePageBulkOperationMutationResolver
    {
        if ($this->createPageBulkOperationMutationResolver === null) {
            /** @var CreatePageBulkOperationMutationResolver */
            $createPageBulkOperationMutationResolver = $this->instanceManager->getInstance(CreatePageBulkOperationMutationResolver::class);
            $this->createPageBulkOperationMutationResolver = $createPageBulkOperationMutationResolver;
        }
        return $this->createPageBulkOperationMutationResolver;
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
    final protected function getUpdatePageBulkOperationMutationResolver(): UpdatePageBulkOperationMutationResolver
    {
        if ($this->updatePageBulkOperationMutationResolver === null) {
            /** @var UpdatePageBulkOperationMutationResolver */
            $updatePageBulkOperationMutationResolver = $this->instanceManager->getInstance(UpdatePageBulkOperationMutationResolver::class);
            $this->updatePageBulkOperationMutationResolver = $updatePageBulkOperationMutationResolver;
        }
        return $this->updatePageBulkOperationMutationResolver;
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
    final protected function getPayloadableUpdatePageBulkOperationMutationResolver(): PayloadableUpdatePageBulkOperationMutationResolver
    {
        if ($this->payloadableUpdatePageBulkOperationMutationResolver === null) {
            /** @var PayloadableUpdatePageBulkOperationMutationResolver */
            $payloadableUpdatePageBulkOperationMutationResolver = $this->instanceManager->getInstance(PayloadableUpdatePageBulkOperationMutationResolver::class);
            $this->payloadableUpdatePageBulkOperationMutationResolver = $payloadableUpdatePageBulkOperationMutationResolver;
        }
        return $this->payloadableUpdatePageBulkOperationMutationResolver;
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
    final protected function getPayloadableCreatePageBulkOperationMutationResolver(): PayloadableCreatePageBulkOperationMutationResolver
    {
        if ($this->payloadableCreatePageBulkOperationMutationResolver === null) {
            /** @var PayloadableCreatePageBulkOperationMutationResolver */
            $payloadableCreatePageBulkOperationMutationResolver = $this->instanceManager->getInstance(PayloadableCreatePageBulkOperationMutationResolver::class);
            $this->payloadableCreatePageBulkOperationMutationResolver = $payloadableCreatePageBulkOperationMutationResolver;
        }
        return $this->payloadableCreatePageBulkOperationMutationResolver;
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
    final protected function getRootCreatePageInputObjectTypeResolver(): RootCreatePageInputObjectTypeResolver
    {
        if ($this->rootCreatePageInputObjectTypeResolver === null) {
            /** @var RootCreatePageInputObjectTypeResolver */
            $rootCreatePageInputObjectTypeResolver = $this->instanceManager->getInstance(RootCreatePageInputObjectTypeResolver::class);
            $this->rootCreatePageInputObjectTypeResolver = $rootCreatePageInputObjectTypeResolver;
        }
        return $this->rootCreatePageInputObjectTypeResolver;
    }
    final protected function getRootDeletePageMutationPayloadObjectTypeResolver(): RootDeletePageMutationPayloadObjectTypeResolver
    {
        if ($this->rootDeletePageMutationPayloadObjectTypeResolver === null) {
            /** @var RootDeletePageMutationPayloadObjectTypeResolver */
            $rootDeletePageMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootDeletePageMutationPayloadObjectTypeResolver::class);
            $this->rootDeletePageMutationPayloadObjectTypeResolver = $rootDeletePageMutationPayloadObjectTypeResolver;
        }
        return $this->rootDeletePageMutationPayloadObjectTypeResolver;
    }
    final protected function getDeletePageMutationResolver(): DeletePageMutationResolver
    {
        if ($this->deletePageMutationResolver === null) {
            /** @var DeletePageMutationResolver */
            $deletePageMutationResolver = $this->instanceManager->getInstance(DeletePageMutationResolver::class);
            $this->deletePageMutationResolver = $deletePageMutationResolver;
        }
        return $this->deletePageMutationResolver;
    }
    final protected function getDeletePageBulkOperationMutationResolver(): DeletePageBulkOperationMutationResolver
    {
        if ($this->deletePageBulkOperationMutationResolver === null) {
            /** @var DeletePageBulkOperationMutationResolver */
            $deletePageBulkOperationMutationResolver = $this->instanceManager->getInstance(DeletePageBulkOperationMutationResolver::class);
            $this->deletePageBulkOperationMutationResolver = $deletePageBulkOperationMutationResolver;
        }
        return $this->deletePageBulkOperationMutationResolver;
    }
    final protected function getPayloadableDeletePageMutationResolver(): PayloadableDeletePageMutationResolver
    {
        if ($this->payloadableDeletePageMutationResolver === null) {
            /** @var PayloadableDeletePageMutationResolver */
            $payloadableDeletePageMutationResolver = $this->instanceManager->getInstance(PayloadableDeletePageMutationResolver::class);
            $this->payloadableDeletePageMutationResolver = $payloadableDeletePageMutationResolver;
        }
        return $this->payloadableDeletePageMutationResolver;
    }
    final protected function getPayloadableDeletePageBulkOperationMutationResolver(): PayloadableDeletePageBulkOperationMutationResolver
    {
        if ($this->payloadableDeletePageBulkOperationMutationResolver === null) {
            /** @var PayloadableDeletePageBulkOperationMutationResolver */
            $payloadableDeletePageBulkOperationMutationResolver = $this->instanceManager->getInstance(PayloadableDeletePageBulkOperationMutationResolver::class);
            $this->payloadableDeletePageBulkOperationMutationResolver = $payloadableDeletePageBulkOperationMutationResolver;
        }
        return $this->payloadableDeletePageBulkOperationMutationResolver;
    }
    final protected function getRootDeletePageInputObjectTypeResolver(): RootDeletePageInputObjectTypeResolver
    {
        if ($this->rootDeletePageInputObjectTypeResolver === null) {
            /** @var RootDeletePageInputObjectTypeResolver */
            $rootDeletePageInputObjectTypeResolver = $this->instanceManager->getInstance(RootDeletePageInputObjectTypeResolver::class);
            $this->rootDeletePageInputObjectTypeResolver = $rootDeletePageInputObjectTypeResolver;
        }
        return $this->rootDeletePageInputObjectTypeResolver;
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
                'createPages',
            ],
            !$disableRedundantRootTypeMutationFields ? [
                'updatePage',
                'updatePages',
                'deletePage',
                'deletePages',
            ] : [],
            $addFieldsToQueryPayloadableCustomPostMutations ? [
                'createPageMutationPayloadObjects',
            ] : [],
            $addFieldsToQueryPayloadableCustomPostMutations && !$disableRedundantRootTypeMutationFields ? [
                'updatePageMutationPayloadObjects',
                'deletePageMutationPayloadObjects',
            ] : [],
        );
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'createPage' => $this->__('Create a page', 'gatographql'),
            'createPages' => $this->__('Create pages', 'gatographql'),
            'updatePage' => $this->__('Update a page', 'gatographql'),
            'updatePages' => $this->__('Update pages', 'gatographql'),
            'deletePage' => $this->__('Delete a page', 'gatographql'),
            'deletePages' => $this->__('Delete pages', 'gatographql'),
            'createPageMutationPayloadObjects' => $this->__('Retrieve the payload objects from a recently-executed `createPage` mutation', 'gatographql'),
            'updatePageMutationPayloadObjects' => $this->__('Retrieve the payload objects from a recently-executed `updatePage` mutation', 'gatographql'),
            'deletePageMutationPayloadObjects' => $this->__('Retrieve the payload objects from a recently-executed `deletePage` mutation', 'gatographql'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        /** @var CustomPostMutationsModuleConfiguration */
        $moduleConfiguration = App::getModule(CustomPostMutationsModule::class)->getConfiguration();
        $usePayloadableCustomPostMutations = $moduleConfiguration->usePayloadableCustomPostMutations();
        if (!$usePayloadableCustomPostMutations) {
            return match ($fieldName) {
                'createPage',
                'updatePage'
                    => SchemaTypeModifiers::NONE,
                'deletePage'
                    => SchemaTypeModifiers::NON_NULLABLE,
                'createPages',
                'updatePages'
                    => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
                'deletePages'
                    => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
                default
                    => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
            };
        }

        if (
            in_array($fieldName, [
            'createPageMutationPayloadObjects',
            'updatePageMutationPayloadObjects',
            'deletePageMutationPayloadObjects',
            ])
        ) {
            return $this->getMutationPayloadObjectsFieldTypeModifiers();
        }

        return match ($fieldName) {
            'createPage',
            'updatePage',
            'deletePage'
                => SchemaTypeModifiers::NON_NULLABLE,
            'createPages',
            'updatePages',
            'deletePages'
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
            'createPages'
                => $this->getBulkOperationFieldArgNameTypeResolvers($this->getRootCreatePageInputObjectTypeResolver()),
            'updatePage' => [
                'input' => $this->getRootUpdatePageInputObjectTypeResolver(),
            ],
            'updatePages'
                => $this->getBulkOperationFieldArgNameTypeResolvers($this->getRootUpdatePageInputObjectTypeResolver()),
            'deletePage' => [
                'input' => $this->getRootDeletePageInputObjectTypeResolver(),
            ],
            'deletePages'
                => $this->getBulkOperationFieldArgNameTypeResolvers($this->getRootDeletePageInputObjectTypeResolver()),
            'createPageMutationPayloadObjects',
            'updatePageMutationPayloadObjects',
            'deletePageMutationPayloadObjects'
                => $this->getMutationPayloadObjectsFieldArgNameTypeResolvers(),
            default
                => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        if (
            in_array($fieldName, [
            'createPageMutationPayloadObjects',
            'updatePageMutationPayloadObjects',
            'deletePageMutationPayloadObjects',
            ])
        ) {
            return $this->getMutationPayloadObjectsFieldArgTypeModifiers($fieldArgName)
                ?? parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
        }

        if (
            in_array($fieldName, [
            'createPages',
            'updatePages',
            'deletePages',
            ])
        ) {
            return $this->getBulkOperationFieldArgTypeModifiers($fieldArgName)
                ?? parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
        }

        return match ([$fieldName => $fieldArgName]) {
            ['createPage' => 'input'],
            ['updatePage' => 'input'],
            ['deletePage' => 'input']
                => SchemaTypeModifiers::MANDATORY,
            default => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldArgDefaultValue(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): mixed
    {
        if (
            in_array($fieldName, [
            'createPages',
            'updatePages',
            'deletePages',
            ])
        ) {
            return $this->getBulkOperationFieldArgDefaultValue($fieldArgName)
                ?? parent::getFieldArgDefaultValue($objectTypeResolver, $fieldName, $fieldArgName);
        }

        return parent::getFieldArgDefaultValue($objectTypeResolver, $fieldName, $fieldArgName);
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
            'createPages' => $usePayloadableCustomPostMutations
                ? $this->getPayloadableCreatePageBulkOperationMutationResolver()
                : $this->getCreatePageBulkOperationMutationResolver(),
            'updatePage' => $usePayloadableCustomPostMutations
                ? $this->getPayloadableUpdatePageMutationResolver()
                : $this->getUpdatePageMutationResolver(),
            'updatePages' => $usePayloadableCustomPostMutations
                ? $this->getPayloadableUpdatePageBulkOperationMutationResolver()
                : $this->getUpdatePageBulkOperationMutationResolver(),
            'deletePage' => $usePayloadableCustomPostMutations
                ? $this->getPayloadableDeletePageMutationResolver()
                : $this->getDeletePageMutationResolver(),
            'deletePages' => $usePayloadableCustomPostMutations
                ? $this->getPayloadableDeletePageBulkOperationMutationResolver()
                : $this->getDeletePageBulkOperationMutationResolver(),
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
                'createPages',
                'createPageMutationPayloadObjects'
                    => $this->getRootCreatePageMutationPayloadObjectTypeResolver(),
                'updatePage',
                'updatePages',
                'updatePageMutationPayloadObjects'
                    => $this->getRootUpdatePageMutationPayloadObjectTypeResolver(),
                'deletePage',
                'deletePages',
                'deletePageMutationPayloadObjects'
                    => $this->getRootDeletePageMutationPayloadObjectTypeResolver(),
                default
                    => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
            };
        }
        return match ($fieldName) {
            'createPage',
            'createPages',
            'updatePage',
            'updatePages'
                => $this->getPageObjectTypeResolver(),
            'deletePage',
            'deletePages'
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
         * @var CustomPostMutationsModuleConfiguration
         */
        $moduleConfiguration = App::getModule(CustomPostMutationsModule::class)->getConfiguration();
        $usePayloadableCustomPostMutations = $moduleConfiguration->usePayloadableCustomPostMutations();
        if ($usePayloadableCustomPostMutations) {
            return $validationCheckpoints;
        }

        switch ($fieldDataAccessor->getFieldName()) {
            case 'createPage':
            case 'createPages':
            case 'updatePage':
            case 'updatePages':
            case 'deletePage':
            case 'deletePages':
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
            case 'deletePageMutationPayloadObjects':
                return $this->resolveMutationPayloadObjectsValue(
                    $objectTypeResolver,
                    $fieldDataAccessor,
                );
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
