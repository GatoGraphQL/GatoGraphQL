<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\MenuMutations\Constants\MutationInputProperties;
use PoPCMSSchema\MenuMutations\Module as MenuMutationsModule;
use PoPCMSSchema\MenuMutations\Module;
use PoPCMSSchema\MenuMutations\ModuleConfiguration as MenuMutationsModuleConfiguration;
use PoPCMSSchema\MenuMutations\ModuleConfiguration;
use PoPCMSSchema\MenuMutations\MutationResolvers\PayloadableUpdateMenuMutationResolver;
use PoPCMSSchema\MenuMutations\MutationResolvers\UpdateMenuMutationResolver;
use PoPCMSSchema\MenuMutations\TypeResolvers\InputObjectType\MenuUpdateInputObjectTypeResolver;
use PoPCMSSchema\MenuMutations\TypeResolvers\ObjectType\MenuUpdateMutationPayloadObjectTypeResolver;
use PoPCMSSchema\Menus\TypeResolvers\ObjectType\MenuObjectTypeResolver;
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

class MenuObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?MenuObjectTypeResolver $menuObjectTypeResolver = null;
    private ?MenuUpdateMutationPayloadObjectTypeResolver $menuUpdateMutationPayloadObjectTypeResolver = null;
    private ?UpdateMenuMutationResolver $updateMenuMutationResolver = null;
    private ?PayloadableUpdateMenuMutationResolver $payloadableUpdateMenuMutationResolver = null;
    private ?MenuUpdateInputObjectTypeResolver $menuUpdateInputObjectTypeResolver = null;
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
    final protected function getMenuUpdateMutationPayloadObjectTypeResolver(): MenuUpdateMutationPayloadObjectTypeResolver
    {
        if ($this->menuUpdateMutationPayloadObjectTypeResolver === null) {
            /** @var MenuUpdateMutationPayloadObjectTypeResolver */
            $menuUpdateMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(MenuUpdateMutationPayloadObjectTypeResolver::class);
            $this->menuUpdateMutationPayloadObjectTypeResolver = $menuUpdateMutationPayloadObjectTypeResolver;
        }
        return $this->menuUpdateMutationPayloadObjectTypeResolver;
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
    final protected function getPayloadableUpdateMenuMutationResolver(): PayloadableUpdateMenuMutationResolver
    {
        if ($this->payloadableUpdateMenuMutationResolver === null) {
            /** @var PayloadableUpdateMenuMutationResolver */
            $payloadableUpdateMenuMutationResolver = $this->instanceManager->getInstance(PayloadableUpdateMenuMutationResolver::class);
            $this->payloadableUpdateMenuMutationResolver = $payloadableUpdateMenuMutationResolver;
        }
        return $this->payloadableUpdateMenuMutationResolver;
    }
    final protected function getMenuUpdateInputObjectTypeResolver(): MenuUpdateInputObjectTypeResolver
    {
        if ($this->menuUpdateInputObjectTypeResolver === null) {
            /** @var MenuUpdateInputObjectTypeResolver */
            $menuUpdateInputObjectTypeResolver = $this->instanceManager->getInstance(MenuUpdateInputObjectTypeResolver::class);
            $this->menuUpdateInputObjectTypeResolver = $menuUpdateInputObjectTypeResolver;
        }
        return $this->menuUpdateInputObjectTypeResolver;
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
            MenuObjectTypeResolver::class,
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
            'update' => $this->__('Update the menu', 'menu-mutations'),
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
                'input' => $this->getMenuUpdateInputObjectTypeResolver(),
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
        $menu = $object;
        switch ($field->getName()) {
            case 'update':
                /** @var stdClass */
                $input = &$fieldArgsForMutationForObject['input'];
                $input->{MutationInputProperties::ID} = $objectTypeResolver->getID($menu);
                break;
        }
        return $fieldArgsForMutationForObject;
    }

    public function getFieldMutationResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?MutationResolverInterface
    {
        /** @var MenuMutationsModuleConfiguration */
        $moduleConfiguration = App::getModule(MenuMutationsModule::class)->getConfiguration();
        $usePayloadableMenuMutations = $moduleConfiguration->usePayloadableMenuMutations();
        return match ($fieldName) {
            'update' => $usePayloadableMenuMutations
                ? $this->getPayloadableUpdateMenuMutationResolver()
                : $this->getUpdateMenuMutationResolver(),
            default => parent::getFieldMutationResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        /** @var MenuMutationsModuleConfiguration */
        $moduleConfiguration = App::getModule(MenuMutationsModule::class)->getConfiguration();
        $usePayloadableMenuMutations = $moduleConfiguration->usePayloadableMenuMutations();
        return match ($fieldName) {
            'update' => $usePayloadableMenuMutations
                ? $this->getMenuUpdateMutationPayloadObjectTypeResolver()
                : $this->getMenuObjectTypeResolver(),
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
        $usePayloadableMenuMutations = $moduleConfiguration->usePayloadableMenuMutations();
        if ($usePayloadableMenuMutations) {
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
