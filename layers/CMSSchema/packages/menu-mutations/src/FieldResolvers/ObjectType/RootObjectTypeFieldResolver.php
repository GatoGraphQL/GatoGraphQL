<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\MenuMutations\Module;
use PoPCMSSchema\MenuMutations\ModuleConfiguration;
use PoPCMSSchema\MenuMutations\MutationResolvers\CreateMenuBulkOperationMutationResolver;
use PoPCMSSchema\MenuMutations\MutationResolvers\CreateMenuMutationResolver;
use PoPCMSSchema\MenuMutations\MutationResolvers\PayloadableCreateMenuBulkOperationMutationResolver;
use PoPCMSSchema\MenuMutations\MutationResolvers\PayloadableCreateMenuMutationResolver;
use PoPCMSSchema\MenuMutations\MutationResolvers\PayloadableUpdateMenuBulkOperationMutationResolver;
use PoPCMSSchema\MenuMutations\MutationResolvers\PayloadableUpdateMenuMutationResolver;
use PoPCMSSchema\MenuMutations\MutationResolvers\UpdateMenuBulkOperationMutationResolver;
use PoPCMSSchema\MenuMutations\MutationResolvers\UpdateMenuMutationResolver;
use PoPCMSSchema\MenuMutations\TypeResolvers\InputObjectType\RootCreateMenuInputObjectTypeResolver;
use PoPCMSSchema\MenuMutations\TypeResolvers\InputObjectType\RootUpdateMenuInputObjectTypeResolver;
use PoPCMSSchema\MenuMutations\TypeResolvers\ObjectType\RootCreateMenuMutationPayloadObjectTypeResolver;
use PoPCMSSchema\MenuMutations\TypeResolvers\ObjectType\RootUpdateMenuMutationPayloadObjectTypeResolver;
use PoPCMSSchema\Menus\TypeResolvers\ObjectType\MenuObjectTypeResolver;
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

    private ?MenuObjectTypeResolver $menuObjectTypeResolver = null;
    private ?CreateMenuMutationResolver $createMenuMutationResolver = null;
    private ?CreateMenuBulkOperationMutationResolver $createMenuBulkOperationMutationResolver = null;
    private ?UpdateMenuMutationResolver $updateMenuMutationResolver = null;
    private ?UpdateMenuBulkOperationMutationResolver $updateMenuBulkOperationMutationResolver = null;
    private ?RootCreateMenuInputObjectTypeResolver $rootCreateMenuInputObjectTypeResolver = null;
    private ?RootCreateMenuMutationPayloadObjectTypeResolver $rootCreateMenuMutationPayloadObjectTypeResolver = null;
    private ?PayloadableCreateMenuMutationResolver $payloadableCreateMenuMutationResolver = null;
    private ?PayloadableCreateMenuBulkOperationMutationResolver $payloadableCreateMenuBulkOperationMutationResolver = null;
    private ?RootUpdateMenuInputObjectTypeResolver $rootUpdateMenuInputObjectTypeResolver = null;
    private ?RootUpdateMenuMutationPayloadObjectTypeResolver $rootUpdateMenuMutationPayloadObjectTypeResolver = null;
    private ?PayloadableUpdateMenuMutationResolver $payloadableUpdateMenuMutationResolver = null;
    private ?PayloadableUpdateMenuBulkOperationMutationResolver $payloadableUpdateMenuBulkOperationMutationResolver = null;
    private ?UserLoggedInCheckpoint $userLoggedInCheckpoint = null;

    final protected function getMenuObjectTypeResolver(): MenuObjectTypeResolver
    {
        if ($this->menuObjectTypeResolver === null) {
            /** @var MenuObjectTypeResolver */
            $menuObjectTypeResolver = $this->instanceManager->getInstance(MenuObjectTypeResolver::class);
            $this->menuObjectTypeResolver = $menuObjectTypeResolver;
        }
        return $this->menuObjectTypeResolver;
    }
    final protected function getCreateMenuMutationResolver(): CreateMenuMutationResolver
    {
        if ($this->createMenuMutationResolver === null) {
            /** @var CreateMenuMutationResolver */
            $createMenuMutationResolver = $this->instanceManager->getInstance(CreateMenuMutationResolver::class);
            $this->createMenuMutationResolver = $createMenuMutationResolver;
        }
        return $this->createMenuMutationResolver;
    }
    final protected function getCreateMenuBulkOperationMutationResolver(): CreateMenuBulkOperationMutationResolver
    {
        if ($this->createMenuBulkOperationMutationResolver === null) {
            /** @var CreateMenuBulkOperationMutationResolver */
            $createMenuBulkOperationMutationResolver = $this->instanceManager->getInstance(CreateMenuBulkOperationMutationResolver::class);
            $this->createMenuBulkOperationMutationResolver = $createMenuBulkOperationMutationResolver;
        }
        return $this->createMenuBulkOperationMutationResolver;
    }
    final protected function getUpdateMenuMutationResolver(): UpdateMenuMutationResolver
    {
        if ($this->updateMenuMutationResolver === null) {
            /** @var UpdateMenuMutationResolver */
            $updateMenuMutationResolver = $this->instanceManager->getInstance(UpdateMenuMutationResolver::class);
            $this->updateMenuMutationResolver = $updateMenuMutationResolver;
        }
        return $this->updateMenuMutationResolver;
    }
    final protected function getUpdateMenuBulkOperationMutationResolver(): UpdateMenuBulkOperationMutationResolver
    {
        if ($this->updateMenuBulkOperationMutationResolver === null) {
            /** @var UpdateMenuBulkOperationMutationResolver */
            $updateMenuBulkOperationMutationResolver = $this->instanceManager->getInstance(UpdateMenuBulkOperationMutationResolver::class);
            $this->updateMenuBulkOperationMutationResolver = $updateMenuBulkOperationMutationResolver;
        }
        return $this->updateMenuBulkOperationMutationResolver;
    }
    final protected function getRootCreateMenuInputObjectTypeResolver(): RootCreateMenuInputObjectTypeResolver
    {
        if ($this->rootCreateMenuInputObjectTypeResolver === null) {
            /** @var RootCreateMenuInputObjectTypeResolver */
            $rootCreateMenuInputObjectTypeResolver = $this->instanceManager->getInstance(RootCreateMenuInputObjectTypeResolver::class);
            $this->rootCreateMenuInputObjectTypeResolver = $rootCreateMenuInputObjectTypeResolver;
        }
        return $this->rootCreateMenuInputObjectTypeResolver;
    }
    final protected function getRootCreateMenuMutationPayloadObjectTypeResolver(): RootCreateMenuMutationPayloadObjectTypeResolver
    {
        if ($this->rootCreateMenuMutationPayloadObjectTypeResolver === null) {
            /** @var RootCreateMenuMutationPayloadObjectTypeResolver */
            $rootCreateMenuMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootCreateMenuMutationPayloadObjectTypeResolver::class);
            $this->rootCreateMenuMutationPayloadObjectTypeResolver = $rootCreateMenuMutationPayloadObjectTypeResolver;
        }
        return $this->rootCreateMenuMutationPayloadObjectTypeResolver;
    }
    final protected function getPayloadableCreateMenuMutationResolver(): PayloadableCreateMenuMutationResolver
    {
        if ($this->payloadableCreateMenuMutationResolver === null) {
            /** @var PayloadableCreateMenuMutationResolver */
            $payloadableCreateMenuMutationResolver = $this->instanceManager->getInstance(PayloadableCreateMenuMutationResolver::class);
            $this->payloadableCreateMenuMutationResolver = $payloadableCreateMenuMutationResolver;
        }
        return $this->payloadableCreateMenuMutationResolver;
    }
    final protected function getPayloadableCreateMenuBulkOperationMutationResolver(): PayloadableCreateMenuBulkOperationMutationResolver
    {
        if ($this->payloadableCreateMenuBulkOperationMutationResolver === null) {
            /** @var PayloadableCreateMenuBulkOperationMutationResolver */
            $payloadableCreateMenuBulkOperationMutationResolver = $this->instanceManager->getInstance(PayloadableCreateMenuBulkOperationMutationResolver::class);
            $this->payloadableCreateMenuBulkOperationMutationResolver = $payloadableCreateMenuBulkOperationMutationResolver;
        }
        return $this->payloadableCreateMenuBulkOperationMutationResolver;
    }
    final protected function getRootUpdateMenuInputObjectTypeResolver(): RootUpdateMenuInputObjectTypeResolver
    {
        if ($this->rootUpdateMenuInputObjectTypeResolver === null) {
            /** @var RootUpdateMenuInputObjectTypeResolver */
            $rootUpdateMenuInputObjectTypeResolver = $this->instanceManager->getInstance(RootUpdateMenuInputObjectTypeResolver::class);
            $this->rootUpdateMenuInputObjectTypeResolver = $rootUpdateMenuInputObjectTypeResolver;
        }
        return $this->rootUpdateMenuInputObjectTypeResolver;
    }
    final protected function getRootUpdateMenuMutationPayloadObjectTypeResolver(): RootUpdateMenuMutationPayloadObjectTypeResolver
    {
        if ($this->rootUpdateMenuMutationPayloadObjectTypeResolver === null) {
            /** @var RootUpdateMenuMutationPayloadObjectTypeResolver */
            $rootUpdateMenuMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootUpdateMenuMutationPayloadObjectTypeResolver::class);
            $this->rootUpdateMenuMutationPayloadObjectTypeResolver = $rootUpdateMenuMutationPayloadObjectTypeResolver;
        }
        return $this->rootUpdateMenuMutationPayloadObjectTypeResolver;
    }
    final protected function getPayloadableUpdateMenuMutationResolver(): PayloadableUpdateMenuMutationResolver
    {
        if ($this->payloadableUpdateMenuMutationResolver === null) {
            /** @var PayloadableUpdateMenuMutationResolver */
            $payloadableUpdateMenuMutationResolver = $this->instanceManager->getInstance(PayloadableUpdateMenuMutationResolver::class);
            $this->payloadableUpdateMenuMutationResolver = $payloadableUpdateMenuMutationResolver;
        }
        return $this->payloadableUpdateMenuMutationResolver;
    }
    final protected function getPayloadableUpdateMenuBulkOperationMutationResolver(): PayloadableUpdateMenuBulkOperationMutationResolver
    {
        if ($this->payloadableUpdateMenuBulkOperationMutationResolver === null) {
            /** @var PayloadableUpdateMenuBulkOperationMutationResolver */
            $payloadableUpdateMenuBulkOperationMutationResolver = $this->instanceManager->getInstance(PayloadableUpdateMenuBulkOperationMutationResolver::class);
            $this->payloadableUpdateMenuBulkOperationMutationResolver = $payloadableUpdateMenuBulkOperationMutationResolver;
        }
        return $this->payloadableUpdateMenuBulkOperationMutationResolver;
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
        $addFieldsToQueryPayloadableMenuMutations = $moduleConfiguration->addFieldsToQueryPayloadableMenuMutations();
        return array_merge(
            [
                'createMenu',
                'createMenus',
            ],
            !$disableRedundantRootTypeMutationFields ? [
                'updateMenu',
                'updateMenus',
            ] : [],
            $addFieldsToQueryPayloadableMenuMutations ? [
                'createMenuMutationPayloadObjects',
            ] : [],
            $addFieldsToQueryPayloadableMenuMutations && !$disableRedundantRootTypeMutationFields ? [
                'updateMenuMutationPayloadObjects',
            ] : [],
        );
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'createMenu' => $this->__('Create a menu', 'menu-mutations'),
            'createMenus' => $this->__('Create menus', 'menu-mutations'),
            'updateMenu' => $this->__('Update a menu', 'menu-mutations'),
            'updateMenus' => $this->__('Update menus', 'menu-mutations'),
            'createMenuMutationPayloadObjects' => $this->__('Retrieve the payload objects from a recently-executed `createMenu` mutation', 'menu-mutations'),
            'updateMenuMutationPayloadObjects' => $this->__('Retrieve the payload objects from a recently-executed `updateMenu` mutation', 'menu-mutations'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableMenuMutations = $moduleConfiguration->usePayloadableMenuMutations();
        if (!$usePayloadableMenuMutations) {
            return match ($fieldName) {
                'createMenu',
                'updateMenu'
                    => SchemaTypeModifiers::NONE,
                'createMenus',
                'updateMenus'
                    => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
                default
                    => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
            };
        }

        if (
            in_array($fieldName, [
            'createMenuMutationPayloadObjects',
            'updateMenuMutationPayloadObjects',
            ])
        ) {
            return $this->getMutationPayloadObjectsFieldTypeModifiers();
        }

        return match ($fieldName) {
            'createMenu',
            'updateMenu'
                => SchemaTypeModifiers::NON_NULLABLE,
            'createMenus',
            'updateMenus'
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
            'createMenu' => [
                'input' => $this->getRootCreateMenuInputObjectTypeResolver(),
            ],
            'createMenus' => $this->getBulkOperationFieldArgNameTypeResolvers($this->getRootCreateMenuInputObjectTypeResolver()),
            'updateMenu' => [
                'input' => $this->getRootUpdateMenuInputObjectTypeResolver(),
            ],
            'updateMenus' => $this->getBulkOperationFieldArgNameTypeResolvers($this->getRootUpdateMenuInputObjectTypeResolver()),
            'createMenuMutationPayloadObjects',
            'updateMenuMutationPayloadObjects'
                => $this->getMutationPayloadObjectsFieldArgNameTypeResolvers(),
            default
                => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        if (
            in_array($fieldName, [
            'createMenuMutationPayloadObjects',
            'updateMenuMutationPayloadObjects',
            ])
        ) {
            return $this->getMutationPayloadObjectsFieldArgTypeModifiers($fieldArgName)
                ?? parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
        }

        if (
            in_array($fieldName, [
            'createMenus',
            'updateMenus',
            ])
        ) {
            return $this->getBulkOperationFieldArgTypeModifiers($fieldArgName)
                ?? parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
        }

        return match ([$fieldName => $fieldArgName]) {
            ['createMenu' => 'input'],
            ['updateMenu' => 'input']
                => SchemaTypeModifiers::MANDATORY,
            default => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldArgDefaultValue(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): mixed
    {
        if (
            in_array($fieldName, [
            'createMenus',
            'updateMenus',
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
        $usePayloadableMenuMutations = $moduleConfiguration->usePayloadableMenuMutations();
        return match ($fieldName) {
            'createMenu' => $usePayloadableMenuMutations
                ? $this->getPayloadableCreateMenuMutationResolver()
                : $this->getCreateMenuMutationResolver(),
            'createMenus' => $usePayloadableMenuMutations
                ? $this->getPayloadableCreateMenuBulkOperationMutationResolver()
                : $this->getCreateMenuBulkOperationMutationResolver(),
            'updateMenu' => $usePayloadableMenuMutations
                ? $this->getPayloadableUpdateMenuMutationResolver()
                : $this->getUpdateMenuMutationResolver(),
            'updateMenus' => $usePayloadableMenuMutations
                ? $this->getPayloadableUpdateMenuBulkOperationMutationResolver()
                : $this->getUpdateMenuBulkOperationMutationResolver(),
            default => parent::getFieldMutationResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableMenuMutations = $moduleConfiguration->usePayloadableMenuMutations();
        if ($usePayloadableMenuMutations) {
            return match ($fieldName) {
                'createMenu',
                'createMenus',
                'createMenuMutationPayloadObjects'
                    => $this->getRootCreateMenuMutationPayloadObjectTypeResolver(),
                'updateMenu',
                'updateMenus',
                'updateMenuMutationPayloadObjects'
                    => $this->getRootUpdateMenuMutationPayloadObjectTypeResolver(),
                default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
            };
        }
        return match ($fieldName) {
            'createMenu',
            'createMenus',
            'updateMenu',
            'updateMenus'
                => $this->getMenuObjectTypeResolver(),
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
        $usePayloadableMenuMutations = $moduleConfiguration->usePayloadableMenuMutations();
        if ($usePayloadableMenuMutations) {
            return $validationCheckpoints;
        }

        switch ($fieldDataAccessor->getFieldName()) {
            case 'createMenu':
            case 'createMenus':
            case 'updateMenu':
            case 'updateMenus':
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
            case 'createMenuMutationPayloadObjects':
            case 'updateMenuMutationPayloadObjects':
                return $this->resolveMutationPayloadObjectsValue(
                    $objectTypeResolver,
                    $fieldDataAccessor,
                );
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
