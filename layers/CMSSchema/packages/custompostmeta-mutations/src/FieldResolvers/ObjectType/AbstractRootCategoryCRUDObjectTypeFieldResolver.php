<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\InputObjectType\RootAddCustomPostMetaInputObjectTypeResolver;
use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\InputObjectType\RootDeleteCustomPostMetaInputObjectTypeResolver;
use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\InputObjectType\RootSetCustomPostMetaInputObjectTypeResolver;
use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\InputObjectType\RootUpdateCustomPostMetaInputObjectTypeResolver;
use PoPCMSSchema\CustomPostMetaMutations\Module;
use PoPCMSSchema\CustomPostMetaMutations\ModuleConfiguration;
use PoPCMSSchema\CustomPostMetaMutations\MutationResolvers\AddCustomPostMetaBulkOperationMutationResolver;
use PoPCMSSchema\CustomPostMetaMutations\MutationResolvers\AddCustomPostMetaMutationResolver;
use PoPCMSSchema\CustomPostMetaMutations\MutationResolvers\DeleteCustomPostMetaBulkOperationMutationResolver;
use PoPCMSSchema\CustomPostMetaMutations\MutationResolvers\DeleteCustomPostMetaMutationResolver;
use PoPCMSSchema\CustomPostMetaMutations\MutationResolvers\PayloadableAddCustomPostMetaBulkOperationMutationResolver;
use PoPCMSSchema\CustomPostMetaMutations\MutationResolvers\PayloadableAddCustomPostMetaMutationResolver;
use PoPCMSSchema\CustomPostMetaMutations\MutationResolvers\PayloadableDeleteCustomPostMetaBulkOperationMutationResolver;
use PoPCMSSchema\CustomPostMetaMutations\MutationResolvers\PayloadableDeleteCustomPostMetaMutationResolver;
use PoPCMSSchema\CustomPostMetaMutations\MutationResolvers\PayloadableSetCustomPostMetaBulkOperationMutationResolver;
use PoPCMSSchema\CustomPostMetaMutations\MutationResolvers\PayloadableSetCustomPostMetaMutationResolver;
use PoPCMSSchema\CustomPostMetaMutations\MutationResolvers\PayloadableUpdateCustomPostMetaBulkOperationMutationResolver;
use PoPCMSSchema\CustomPostMetaMutations\MutationResolvers\PayloadableUpdateCustomPostMetaMutationResolver;
use PoPCMSSchema\CustomPostMetaMutations\MutationResolvers\SetCustomPostMetaBulkOperationMutationResolver;
use PoPCMSSchema\CustomPostMetaMutations\MutationResolvers\SetCustomPostMetaMutationResolver;
use PoPCMSSchema\CustomPostMetaMutations\MutationResolvers\UpdateCustomPostMetaBulkOperationMutationResolver;
use PoPCMSSchema\CustomPostMetaMutations\MutationResolvers\UpdateCustomPostMetaMutationResolver;
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

abstract class AbstractRootCustomPostCRUDObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    use MutationPayloadObjectsObjectTypeFieldResolverTrait;
    use BulkOperationDecoratorObjectTypeFieldResolverTrait;

    private ?AddCustomPostMetaMutationResolver $addCustomPostMetaMutationResolver = null;
    private ?AddCustomPostMetaBulkOperationMutationResolver $addCustomPostMetaBulkOperationMutationResolver = null;
    private ?DeleteCustomPostMetaMutationResolver $deleteCustomPostMetaMutationResolver = null;
    private ?DeleteCustomPostMetaBulkOperationMutationResolver $deleteCustomPostMetaBulkOperationMutationResolver = null;
    private ?SetCustomPostMetaMutationResolver $setCustomPostMetaMutationResolver = null;
    private ?SetCustomPostMetaBulkOperationMutationResolver $setCustomPostMetaBulkOperationMutationResolver = null;
    private ?UpdateCustomPostMetaMutationResolver $updateCustomPostMetaMutationResolver = null;
    private ?UpdateCustomPostMetaBulkOperationMutationResolver $updateCustomPostMetaBulkOperationMutationResolver = null;
    private ?PayloadableDeleteCustomPostMetaMutationResolver $payloadableDeleteCustomPostMetaMutationResolver = null;
    private ?PayloadableDeleteCustomPostMetaBulkOperationMutationResolver $payloadableDeleteCustomPostMetaBulkOperationMutationResolver = null;
    private ?PayloadableSetCustomPostMetaMutationResolver $payloadableSetCustomPostMetaMutationResolver = null;
    private ?PayloadableSetCustomPostMetaBulkOperationMutationResolver $payloadableSetCustomPostMetaBulkOperationMutationResolver = null;
    private ?PayloadableUpdateCustomPostMetaMutationResolver $payloadableUpdateCustomPostMetaMutationResolver = null;
    private ?PayloadableUpdateCustomPostMetaBulkOperationMutationResolver $payloadableUpdateCustomPostMetaBulkOperationMutationResolver = null;
    private ?PayloadableAddCustomPostMetaMutationResolver $payloadableAddCustomPostMetaMutationResolver = null;
    private ?PayloadableAddCustomPostMetaBulkOperationMutationResolver $payloadableAddCustomPostMetaBulkOperationMutationResolver = null;
    private ?RootDeleteCustomPostMetaInputObjectTypeResolver $rootDeleteCustomPostMetaInputObjectTypeResolver = null;
    private ?RootSetCustomPostMetaInputObjectTypeResolver $rootSetCustomPostMetaInputObjectTypeResolver = null;
    private ?RootUpdateCustomPostMetaInputObjectTypeResolver $rootUpdateCustomPostMetaInputObjectTypeResolver = null;
    private ?RootAddCustomPostMetaInputObjectTypeResolver $rootAddCustomPostMetaInputObjectTypeResolver = null;
    private ?UserLoggedInCheckpoint $userLoggedInCheckpoint = null;

    final protected function getAddCustomPostMetaMutationResolver(): AddCustomPostMetaMutationResolver
    {
        if ($this->addCustomPostMetaMutationResolver === null) {
            /** @var AddCustomPostMetaMutationResolver */
            $addCustomPostMetaMutationResolver = $this->instanceManager->getInstance(AddCustomPostMetaMutationResolver::class);
            $this->addCustomPostMetaMutationResolver = $addCustomPostMetaMutationResolver;
        }
        return $this->addCustomPostMetaMutationResolver;
    }
    final protected function getAddCustomPostMetaBulkOperationMutationResolver(): AddCustomPostMetaBulkOperationMutationResolver
    {
        if ($this->addCustomPostMetaBulkOperationMutationResolver === null) {
            /** @var AddCustomPostMetaBulkOperationMutationResolver */
            $addCustomPostMetaBulkOperationMutationResolver = $this->instanceManager->getInstance(AddCustomPostMetaBulkOperationMutationResolver::class);
            $this->addCustomPostMetaBulkOperationMutationResolver = $addCustomPostMetaBulkOperationMutationResolver;
        }
        return $this->addCustomPostMetaBulkOperationMutationResolver;
    }
    final protected function getDeleteCustomPostMetaMutationResolver(): DeleteCustomPostMetaMutationResolver
    {
        if ($this->deleteCustomPostMetaMutationResolver === null) {
            /** @var DeleteCustomPostMetaMutationResolver */
            $deleteCustomPostMetaMutationResolver = $this->instanceManager->getInstance(DeleteCustomPostMetaMutationResolver::class);
            $this->deleteCustomPostMetaMutationResolver = $deleteCustomPostMetaMutationResolver;
        }
        return $this->deleteCustomPostMetaMutationResolver;
    }
    final protected function getDeleteCustomPostMetaBulkOperationMutationResolver(): DeleteCustomPostMetaBulkOperationMutationResolver
    {
        if ($this->deleteCustomPostMetaBulkOperationMutationResolver === null) {
            /** @var DeleteCustomPostMetaBulkOperationMutationResolver */
            $deleteCustomPostMetaBulkOperationMutationResolver = $this->instanceManager->getInstance(DeleteCustomPostMetaBulkOperationMutationResolver::class);
            $this->deleteCustomPostMetaBulkOperationMutationResolver = $deleteCustomPostMetaBulkOperationMutationResolver;
        }
        return $this->deleteCustomPostMetaBulkOperationMutationResolver;
    }
    final protected function getSetCustomPostMetaMutationResolver(): SetCustomPostMetaMutationResolver
    {
        if ($this->setCustomPostMetaMutationResolver === null) {
            /** @var SetCustomPostMetaMutationResolver */
            $setCustomPostMetaMutationResolver = $this->instanceManager->getInstance(SetCustomPostMetaMutationResolver::class);
            $this->setCustomPostMetaMutationResolver = $setCustomPostMetaMutationResolver;
        }
        return $this->setCustomPostMetaMutationResolver;
    }
    final protected function getSetCustomPostMetaBulkOperationMutationResolver(): SetCustomPostMetaBulkOperationMutationResolver
    {
        if ($this->setCustomPostMetaBulkOperationMutationResolver === null) {
            /** @var SetCustomPostMetaBulkOperationMutationResolver */
            $setCustomPostMetaBulkOperationMutationResolver = $this->instanceManager->getInstance(SetCustomPostMetaBulkOperationMutationResolver::class);
            $this->setCustomPostMetaBulkOperationMutationResolver = $setCustomPostMetaBulkOperationMutationResolver;
        }
        return $this->setCustomPostMetaBulkOperationMutationResolver;
    }
    final protected function getUpdateCustomPostMetaMutationResolver(): UpdateCustomPostMetaMutationResolver
    {
        if ($this->updateCustomPostMetaMutationResolver === null) {
            /** @var UpdateCustomPostMetaMutationResolver */
            $updateCustomPostMetaMutationResolver = $this->instanceManager->getInstance(UpdateCustomPostMetaMutationResolver::class);
            $this->updateCustomPostMetaMutationResolver = $updateCustomPostMetaMutationResolver;
        }
        return $this->updateCustomPostMetaMutationResolver;
    }
    final protected function getUpdateCustomPostMetaBulkOperationMutationResolver(): UpdateCustomPostMetaBulkOperationMutationResolver
    {
        if ($this->updateCustomPostMetaBulkOperationMutationResolver === null) {
            /** @var UpdateCustomPostMetaBulkOperationMutationResolver */
            $updateCustomPostMetaBulkOperationMutationResolver = $this->instanceManager->getInstance(UpdateCustomPostMetaBulkOperationMutationResolver::class);
            $this->updateCustomPostMetaBulkOperationMutationResolver = $updateCustomPostMetaBulkOperationMutationResolver;
        }
        return $this->updateCustomPostMetaBulkOperationMutationResolver;
    }
    final protected function getPayloadableDeleteCustomPostMetaMutationResolver(): PayloadableDeleteCustomPostMetaMutationResolver
    {
        if ($this->payloadableDeleteCustomPostMetaMutationResolver === null) {
            /** @var PayloadableDeleteCustomPostMetaMutationResolver */
            $payloadableDeleteCustomPostMetaMutationResolver = $this->instanceManager->getInstance(PayloadableDeleteCustomPostMetaMutationResolver::class);
            $this->payloadableDeleteCustomPostMetaMutationResolver = $payloadableDeleteCustomPostMetaMutationResolver;
        }
        return $this->payloadableDeleteCustomPostMetaMutationResolver;
    }
    final protected function getPayloadableDeleteCustomPostMetaBulkOperationMutationResolver(): PayloadableDeleteCustomPostMetaBulkOperationMutationResolver
    {
        if ($this->payloadableDeleteCustomPostMetaBulkOperationMutationResolver === null) {
            /** @var PayloadableDeleteCustomPostMetaBulkOperationMutationResolver */
            $payloadableDeleteCustomPostMetaBulkOperationMutationResolver = $this->instanceManager->getInstance(PayloadableDeleteCustomPostMetaBulkOperationMutationResolver::class);
            $this->payloadableDeleteCustomPostMetaBulkOperationMutationResolver = $payloadableDeleteCustomPostMetaBulkOperationMutationResolver;
        }
        return $this->payloadableDeleteCustomPostMetaBulkOperationMutationResolver;
    }
    final protected function getPayloadableSetCustomPostMetaMutationResolver(): PayloadableSetCustomPostMetaMutationResolver
    {
        if ($this->payloadableSetCustomPostMetaMutationResolver === null) {
            /** @var PayloadableSetCustomPostMetaMutationResolver */
            $payloadableSetCustomPostMetaMutationResolver = $this->instanceManager->getInstance(PayloadableSetCustomPostMetaMutationResolver::class);
            $this->payloadableSetCustomPostMetaMutationResolver = $payloadableSetCustomPostMetaMutationResolver;
        }
        return $this->payloadableSetCustomPostMetaMutationResolver;
    }
    final protected function getPayloadableSetCustomPostMetaBulkOperationMutationResolver(): PayloadableSetCustomPostMetaBulkOperationMutationResolver
    {
        if ($this->payloadableSetCustomPostMetaBulkOperationMutationResolver === null) {
            /** @var PayloadableSetCustomPostMetaBulkOperationMutationResolver */
            $payloadableSetCustomPostMetaBulkOperationMutationResolver = $this->instanceManager->getInstance(PayloadableSetCustomPostMetaBulkOperationMutationResolver::class);
            $this->payloadableSetCustomPostMetaBulkOperationMutationResolver = $payloadableSetCustomPostMetaBulkOperationMutationResolver;
        }
        return $this->payloadableSetCustomPostMetaBulkOperationMutationResolver;
    }
    final protected function getPayloadableUpdateCustomPostMetaMutationResolver(): PayloadableUpdateCustomPostMetaMutationResolver
    {
        if ($this->payloadableUpdateCustomPostMetaMutationResolver === null) {
            /** @var PayloadableUpdateCustomPostMetaMutationResolver */
            $payloadableUpdateCustomPostMetaMutationResolver = $this->instanceManager->getInstance(PayloadableUpdateCustomPostMetaMutationResolver::class);
            $this->payloadableUpdateCustomPostMetaMutationResolver = $payloadableUpdateCustomPostMetaMutationResolver;
        }
        return $this->payloadableUpdateCustomPostMetaMutationResolver;
    }
    final protected function getPayloadableUpdateCustomPostMetaBulkOperationMutationResolver(): PayloadableUpdateCustomPostMetaBulkOperationMutationResolver
    {
        if ($this->payloadableUpdateCustomPostMetaBulkOperationMutationResolver === null) {
            /** @var PayloadableUpdateCustomPostMetaBulkOperationMutationResolver */
            $payloadableUpdateCustomPostMetaBulkOperationMutationResolver = $this->instanceManager->getInstance(PayloadableUpdateCustomPostMetaBulkOperationMutationResolver::class);
            $this->payloadableUpdateCustomPostMetaBulkOperationMutationResolver = $payloadableUpdateCustomPostMetaBulkOperationMutationResolver;
        }
        return $this->payloadableUpdateCustomPostMetaBulkOperationMutationResolver;
    }
    final protected function getPayloadableAddCustomPostMetaMutationResolver(): PayloadableAddCustomPostMetaMutationResolver
    {
        if ($this->payloadableAddCustomPostMetaMutationResolver === null) {
            /** @var PayloadableAddCustomPostMetaMutationResolver */
            $payloadableAddCustomPostMetaMutationResolver = $this->instanceManager->getInstance(PayloadableAddCustomPostMetaMutationResolver::class);
            $this->payloadableAddCustomPostMetaMutationResolver = $payloadableAddCustomPostMetaMutationResolver;
        }
        return $this->payloadableAddCustomPostMetaMutationResolver;
    }
    final protected function getPayloadableAddCustomPostMetaBulkOperationMutationResolver(): PayloadableAddCustomPostMetaBulkOperationMutationResolver
    {
        if ($this->payloadableAddCustomPostMetaBulkOperationMutationResolver === null) {
            /** @var PayloadableAddCustomPostMetaBulkOperationMutationResolver */
            $payloadableAddCustomPostMetaBulkOperationMutationResolver = $this->instanceManager->getInstance(PayloadableAddCustomPostMetaBulkOperationMutationResolver::class);
            $this->payloadableAddCustomPostMetaBulkOperationMutationResolver = $payloadableAddCustomPostMetaBulkOperationMutationResolver;
        }
        return $this->payloadableAddCustomPostMetaBulkOperationMutationResolver;
    }
    final protected function getRootDeleteCustomPostMetaInputObjectTypeResolver(): RootDeleteCustomPostMetaInputObjectTypeResolver
    {
        if ($this->rootDeleteCustomPostMetaInputObjectTypeResolver === null) {
            /** @var RootDeleteCustomPostMetaInputObjectTypeResolver */
            $rootDeleteCustomPostMetaInputObjectTypeResolver = $this->instanceManager->getInstance(RootDeleteCustomPostMetaInputObjectTypeResolver::class);
            $this->rootDeleteCustomPostMetaInputObjectTypeResolver = $rootDeleteCustomPostMetaInputObjectTypeResolver;
        }
        return $this->rootDeleteCustomPostMetaInputObjectTypeResolver;
    }
    final protected function getRootSetCustomPostMetaInputObjectTypeResolver(): RootSetCustomPostMetaInputObjectTypeResolver
    {
        if ($this->rootSetCustomPostMetaInputObjectTypeResolver === null) {
            /** @var RootSetCustomPostMetaInputObjectTypeResolver */
            $rootSetCustomPostMetaInputObjectTypeResolver = $this->instanceManager->getInstance(RootSetCustomPostMetaInputObjectTypeResolver::class);
            $this->rootSetCustomPostMetaInputObjectTypeResolver = $rootSetCustomPostMetaInputObjectTypeResolver;
        }
        return $this->rootSetCustomPostMetaInputObjectTypeResolver;
    }
    final protected function getRootUpdateCustomPostMetaInputObjectTypeResolver(): RootUpdateCustomPostMetaInputObjectTypeResolver
    {
        if ($this->rootUpdateCustomPostMetaInputObjectTypeResolver === null) {
            /** @var RootUpdateCustomPostMetaInputObjectTypeResolver */
            $rootUpdateCustomPostMetaInputObjectTypeResolver = $this->instanceManager->getInstance(RootUpdateCustomPostMetaInputObjectTypeResolver::class);
            $this->rootUpdateCustomPostMetaInputObjectTypeResolver = $rootUpdateCustomPostMetaInputObjectTypeResolver;
        }
        return $this->rootUpdateCustomPostMetaInputObjectTypeResolver;
    }
    final protected function getRootAddCustomPostMetaInputObjectTypeResolver(): RootAddCustomPostMetaInputObjectTypeResolver
    {
        if ($this->rootAddCustomPostMetaInputObjectTypeResolver === null) {
            /** @var RootAddCustomPostMetaInputObjectTypeResolver */
            $rootAddCustomPostMetaInputObjectTypeResolver = $this->instanceManager->getInstance(RootAddCustomPostMetaInputObjectTypeResolver::class);
            $this->rootAddCustomPostMetaInputObjectTypeResolver = $rootAddCustomPostMetaInputObjectTypeResolver;
        }
        return $this->rootAddCustomPostMetaInputObjectTypeResolver;
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

    abstract protected function getCustomPostEntityName(): string;

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        $categoryEntityName = $this->getCustomPostEntityName();
        /** @var EngineModuleConfiguration */
        $engineModuleConfiguration = App::getModule(EngineModule::class)->getConfiguration();
        $disableRedundantRootTypeMutationFields = $engineModuleConfiguration->disableRedundantRootTypeMutationFields();
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $addFieldsToQueryPayloadableCustomPostMetaMutations = $moduleConfiguration->addFieldsToQueryPayloadableCustomPostMetaMutations();
        return array_merge(
            !$disableRedundantRootTypeMutationFields ? [
                'add' . $categoryEntityName . 'Meta',
                'add' . $categoryEntityName . 'Metas',
                'update' . $categoryEntityName . 'Meta',
                'update' . $categoryEntityName . 'Metas',
                'delete' . $categoryEntityName . 'Meta',
                'delete' . $categoryEntityName . 'Metas',
                'set' . $categoryEntityName . 'Meta',
                'set' . $categoryEntityName . 'Metas',
            ] : [],
            $addFieldsToQueryPayloadableCustomPostMetaMutations && !$disableRedundantRootTypeMutationFields ? [
                'add' . $categoryEntityName . 'MetaMutationPayloadObjects',
                'update' . $categoryEntityName . 'MetaMutationPayloadObjects',
                'delete' . $categoryEntityName . 'MetaMutationPayloadObjects',
                'set' . $categoryEntityName . 'MetaMutationPayloadObjects',
            ] : [],
        );
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $categoryEntityName = $this->getCustomPostEntityName();
        return match ($fieldName) {
            'add' . $categoryEntityName . 'Meta' => $this->__('Add meta to category', 'category-mutations'),
            'add' . $categoryEntityName . 'Metas' => $this->__('Add meta to categories', 'category-mutations'),
            'update' . $categoryEntityName . 'Meta' => $this->__('Update meta from category', 'category-mutations'),
            'update' . $categoryEntityName . 'Metas' => $this->__('Update meta from categories', 'category-mutations'),
            'delete' . $categoryEntityName . 'Meta' => $this->__('Delete meta from category', 'category-mutations'),
            'delete' . $categoryEntityName . 'Metas' => $this->__('Delete meta from categories', 'category-mutations'),
            'set' . $categoryEntityName . 'Meta' => $this->__('Set meta on category', 'category-mutations'),
            'set' . $categoryEntityName . 'Metas' => $this->__('Set meta on categories', 'category-mutations'),
            'add' . $categoryEntityName . 'MetaMutationPayloadObjects' => $this->__('Retrieve the payload objects from a recently-executed `addCustomPostMeta` mutation', 'category-mutations'),
            'update' . $categoryEntityName . 'MetaMutationPayloadObjects' => $this->__('Retrieve the payload objects from a recently-executed `updateCustomPostMeta` mutation', 'category-mutations'),
            'delete' . $categoryEntityName . 'MetaMutationPayloadObjects' => $this->__('Retrieve the payload objects from a recently-executed `deleteCustomPostMeta` mutation', 'category-mutations'),
            'set' . $categoryEntityName . 'MetaMutationPayloadObjects' => $this->__('Retrieve the payload objects from a recently-executed `setCustomPostMeta` mutation', 'category-mutations'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        $categoryEntityName = $this->getCustomPostEntityName();
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableCustomPostMetaMutations = $moduleConfiguration->usePayloadableCustomPostMetaMutations();
        if (!$usePayloadableCustomPostMetaMutations) {
            return match ($fieldName) {
                'add' . $categoryEntityName . 'Meta',
                'update' . $categoryEntityName . 'Meta',
                'delete' . $categoryEntityName . 'Meta',
                'set' . $categoryEntityName . 'Meta'
                    => SchemaTypeModifiers::NONE,
                'add' . $categoryEntityName . 'Metas',
                'update' . $categoryEntityName . 'Metas',
                'delete' . $categoryEntityName . 'Metas',
                'set' . $categoryEntityName . 'Metas'
                    => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
                default
                    => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
            };
        }

        if (
            in_array($fieldName, [
            'add' . $categoryEntityName . 'MetaMutationPayloadObjects',
            'update' . $categoryEntityName . 'MetaMutationPayloadObjects',
            'delete' . $categoryEntityName . 'MetaMutationPayloadObjects',
            'set' . $categoryEntityName . 'MetaMutationPayloadObjects',
            ])
        ) {
            return $this->getMutationPayloadObjectsFieldTypeModifiers();
        }

        return match ($fieldName) {
            'add' . $categoryEntityName . 'Meta',
            'update' . $categoryEntityName . 'Meta',
            'delete' . $categoryEntityName . 'Meta',
            'set' . $categoryEntityName . 'Meta'
                => SchemaTypeModifiers::NON_NULLABLE,
            'add' . $categoryEntityName . 'Metas',
            'update' . $categoryEntityName . 'Metas',
            'delete' . $categoryEntityName . 'Metas',
            'set' . $categoryEntityName . 'Metas'
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
        $categoryEntityName = $this->getCustomPostEntityName();
        return match ($fieldName) {
            'add' . $categoryEntityName . 'Meta' => [
                'input' => $this->getRootAddCustomPostMetaInputObjectTypeResolver(),
            ],
            'add' . $categoryEntityName . 'Metas'
                => $this->getBulkOperationFieldArgNameTypeResolvers($this->getRootAddCustomPostMetaInputObjectTypeResolver()),
            'update' . $categoryEntityName . 'Meta' => [
                'input' => $this->getRootUpdateCustomPostMetaInputObjectTypeResolver(),
            ],
            'update' . $categoryEntityName . 'Metas'
                => $this->getBulkOperationFieldArgNameTypeResolvers($this->getRootUpdateCustomPostMetaInputObjectTypeResolver()),
            'delete' . $categoryEntityName . 'Meta' => [
                'input' => $this->getRootDeleteCustomPostMetaInputObjectTypeResolver(),
            ],
            'delete' . $categoryEntityName . 'Metas'
                => $this->getBulkOperationFieldArgNameTypeResolvers($this->getRootDeleteCustomPostMetaInputObjectTypeResolver()),
            'set' . $categoryEntityName . 'Meta' => [
                'input' => $this->getRootSetCustomPostMetaInputObjectTypeResolver(),
            ],
            'set' . $categoryEntityName . 'Metas'
                => $this->getBulkOperationFieldArgNameTypeResolvers($this->getRootSetCustomPostMetaInputObjectTypeResolver()),
            'add' . $categoryEntityName . 'MetaMutationPayloadObjects',
            'update' . $categoryEntityName . 'MetaMutationPayloadObjects',
            'delete' . $categoryEntityName . 'MetaMutationPayloadObjects',
            'set' . $categoryEntityName . 'MetaMutationPayloadObjects'
                => $this->getMutationPayloadObjectsFieldArgNameTypeResolvers(),
            default
                => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        $categoryEntityName = $this->getCustomPostEntityName();
        if (
            in_array($fieldName, [
            'add' . $categoryEntityName . 'MetaMutationPayloadObjects',
            'update' . $categoryEntityName . 'MetaMutationPayloadObjects',
            'delete' . $categoryEntityName . 'MetaMutationPayloadObjects',
            'set' . $categoryEntityName . 'MetaMutationPayloadObjects',
            ])
        ) {
            return $this->getMutationPayloadObjectsFieldArgTypeModifiers($fieldArgName)
                ?? parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
        }

        if (
            in_array($fieldName, [
            'add' . $categoryEntityName . 'Metas',
            'update' . $categoryEntityName . 'Metas',
            'delete' . $categoryEntityName . 'Metas',
            'set' . $categoryEntityName . 'Metas',
            ])
        ) {
            return $this->getBulkOperationFieldArgTypeModifiers($fieldArgName)
                ?? parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
        }

        return match ([$fieldName => $fieldArgName]) {
            ['add' . $categoryEntityName . 'Meta' => 'input'],
            ['update' . $categoryEntityName . 'Meta' => 'input'],
            ['delete' . $categoryEntityName . 'Meta' => 'input'],
            ['set' . $categoryEntityName . 'Meta' => 'input']
                => SchemaTypeModifiers::MANDATORY,
            default => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldArgDefaultValue(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): mixed
    {
        $categoryEntityName = $this->getCustomPostEntityName();
        if (
            in_array($fieldName, [
            'add' . $categoryEntityName . 'Metas',
            'update' . $categoryEntityName . 'Metas',
            'delete' . $categoryEntityName . 'Metas',
            'set' . $categoryEntityName . 'Metas',
            ])
        ) {
            return $this->getBulkOperationFieldArgDefaultValue($fieldArgName)
                ?? parent::getFieldArgDefaultValue($objectTypeResolver, $fieldName, $fieldArgName);
        }

        return parent::getFieldArgDefaultValue($objectTypeResolver, $fieldName, $fieldArgName);
    }

    public function getFieldMutationResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?MutationResolverInterface
    {
        $categoryEntityName = $this->getCustomPostEntityName();
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableCustomPostMetaMutations = $moduleConfiguration->usePayloadableCustomPostMetaMutations();
        return match ($fieldName) {
            'add' . $categoryEntityName . 'Meta' => $usePayloadableCustomPostMetaMutations
                ? $this->getPayloadableAddCustomPostMetaMutationResolver()
                : $this->getAddCustomPostMetaMutationResolver(),
            'add' . $categoryEntityName . 'Metas' => $usePayloadableCustomPostMetaMutations
                ? $this->getPayloadableAddCustomPostMetaBulkOperationMutationResolver()
                : $this->getAddCustomPostMetaBulkOperationMutationResolver(),
            'update' . $categoryEntityName . 'Meta' => $usePayloadableCustomPostMetaMutations
                ? $this->getPayloadableUpdateCustomPostMetaMutationResolver()
                : $this->getUpdateCustomPostMetaMutationResolver(),
            'update' . $categoryEntityName . 'Metas' => $usePayloadableCustomPostMetaMutations
                ? $this->getPayloadableUpdateCustomPostMetaBulkOperationMutationResolver()
                : $this->getUpdateCustomPostMetaBulkOperationMutationResolver(),
            'delete' . $categoryEntityName . 'Meta' => $usePayloadableCustomPostMetaMutations
                ? $this->getPayloadableDeleteCustomPostMetaMutationResolver()
                : $this->getDeleteCustomPostMetaMutationResolver(),
            'delete' . $categoryEntityName . 'Metas' => $usePayloadableCustomPostMetaMutations
                ? $this->getPayloadableDeleteCustomPostMetaBulkOperationMutationResolver()
                : $this->getDeleteCustomPostMetaBulkOperationMutationResolver(),
            'set' . $categoryEntityName . 'Meta' => $usePayloadableCustomPostMetaMutations
                ? $this->getPayloadableSetCustomPostMetaMutationResolver()
                : $this->getSetCustomPostMetaMutationResolver(),
            'set' . $categoryEntityName . 'Metas' => $usePayloadableCustomPostMetaMutations
                ? $this->getPayloadableSetCustomPostMetaBulkOperationMutationResolver()
                : $this->getSetCustomPostMetaBulkOperationMutationResolver(),
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
        $usePayloadableCustomPostMetaMutations = $moduleConfiguration->usePayloadableCustomPostMetaMutations();
        if ($usePayloadableCustomPostMetaMutations) {
            return $validationCheckpoints;
        }

        $categoryEntityName = $this->getCustomPostEntityName();
        switch ($fieldDataAccessor->getFieldName()) {
            case 'add' . $categoryEntityName . 'Meta':
            case 'add' . $categoryEntityName . 'Metas':
            case 'update' . $categoryEntityName . 'Meta':
            case 'update' . $categoryEntityName . 'Metas':
            case 'delete' . $categoryEntityName . 'Meta':
            case 'delete' . $categoryEntityName . 'Metas':
            case 'set' . $categoryEntityName . 'Meta':
            case 'set' . $categoryEntityName . 'Metas':
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
        $categoryEntityName = $this->getCustomPostEntityName();
        $fieldName = $fieldDataAccessor->getFieldName();
        switch ($fieldName) {
            case 'add' . $categoryEntityName . 'MetaMutationPayloadObjects':
            case 'update' . $categoryEntityName . 'MetaMutationPayloadObjects':
            case 'delete' . $categoryEntityName . 'MetaMutationPayloadObjects':
            case 'set' . $categoryEntityName . 'MetaMutationPayloadObjects':
                return $this->resolveMutationPayloadObjectsValue(
                    $objectTypeResolver,
                    $fieldDataAccessor,
                );
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
