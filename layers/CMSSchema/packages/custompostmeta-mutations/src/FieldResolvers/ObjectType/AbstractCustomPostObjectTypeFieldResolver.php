<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostMetaMutations\Module;
use PoPCMSSchema\CustomPostMetaMutations\ModuleConfiguration;
use PoPCMSSchema\CustomPostMetaMutations\MutationResolvers\AddCustomPostMetaMutationResolver;
use PoPCMSSchema\CustomPostMetaMutations\MutationResolvers\DeleteCustomPostMetaMutationResolver;
use PoPCMSSchema\CustomPostMetaMutations\MutationResolvers\PayloadableAddCustomPostMetaMutationResolver;
use PoPCMSSchema\CustomPostMetaMutations\MutationResolvers\PayloadableDeleteCustomPostMetaMutationResolver;
use PoPCMSSchema\CustomPostMetaMutations\MutationResolvers\PayloadableSetCustomPostMetaMutationResolver;
use PoPCMSSchema\CustomPostMetaMutations\MutationResolvers\PayloadableUpdateCustomPostMetaMutationResolver;
use PoPCMSSchema\CustomPostMetaMutations\MutationResolvers\SetCustomPostMetaMutationResolver;
use PoPCMSSchema\CustomPostMetaMutations\MutationResolvers\UpdateCustomPostMetaMutationResolver;
use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\InputObjectType\CustomPostAddMetaInputObjectTypeResolver;
use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\InputObjectType\CustomPostDeleteMetaInputObjectTypeResolver;
use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\InputObjectType\CustomPostSetMetaInputObjectTypeResolver;
use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\InputObjectType\CustomPostUpdateMetaInputObjectTypeResolver;
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

abstract class AbstractCustomPostObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?UserLoggedInCheckpoint $userLoggedInCheckpoint = null;
    private ?CustomPostAddMetaInputObjectTypeResolver $customPostAddMetaInputObjectTypeResolver = null;
    private ?CustomPostDeleteMetaInputObjectTypeResolver $customPostDeleteMetaInputObjectTypeResolver = null;
    private ?CustomPostSetMetaInputObjectTypeResolver $customPostSetMetaInputObjectTypeResolver = null;
    private ?CustomPostUpdateMetaInputObjectTypeResolver $customPostUpdateMetaInputObjectTypeResolver = null;
    private ?AddCustomPostMetaMutationResolver $addCustomPostMetaMutationResolver = null;
    private ?DeleteCustomPostMetaMutationResolver $deleteCustomPostMetaMutationResolver = null;
    private ?SetCustomPostMetaMutationResolver $setCustomPostMetaMutationResolver = null;
    private ?UpdateCustomPostMetaMutationResolver $updateCustomPostMetaMutationResolver = null;
    private ?PayloadableDeleteCustomPostMetaMutationResolver $payloadableDeleteCustomPostMetaMutationResolver = null;
    private ?PayloadableSetCustomPostMetaMutationResolver $payloadableSetCustomPostMetaMutationResolver = null;
    private ?PayloadableUpdateCustomPostMetaMutationResolver $payloadableUpdateCustomPostMetaMutationResolver = null;
    private ?PayloadableAddCustomPostMetaMutationResolver $payloadableAddCustomPostMetaMutationResolver = null;

    final protected function getUserLoggedInCheckpoint(): UserLoggedInCheckpoint
    {
        if ($this->userLoggedInCheckpoint === null) {
            /** @var UserLoggedInCheckpoint */
            $userLoggedInCheckpoint = $this->instanceManager->getInstance(UserLoggedInCheckpoint::class);
            $this->userLoggedInCheckpoint = $userLoggedInCheckpoint;
        }
        return $this->userLoggedInCheckpoint;
    }
    final protected function getCustomPostAddMetaInputObjectTypeResolver(): CustomPostAddMetaInputObjectTypeResolver
    {
        if ($this->customPostAddMetaInputObjectTypeResolver === null) {
            /** @var CustomPostAddMetaInputObjectTypeResolver */
            $customPostAddMetaInputObjectTypeResolver = $this->instanceManager->getInstance(CustomPostAddMetaInputObjectTypeResolver::class);
            $this->customPostAddMetaInputObjectTypeResolver = $customPostAddMetaInputObjectTypeResolver;
        }
        return $this->customPostAddMetaInputObjectTypeResolver;
    }
    final protected function getCustomPostDeleteMetaInputObjectTypeResolver(): CustomPostDeleteMetaInputObjectTypeResolver
    {
        if ($this->customPostDeleteMetaInputObjectTypeResolver === null) {
            /** @var CustomPostDeleteMetaInputObjectTypeResolver */
            $customPostDeleteMetaInputObjectTypeResolver = $this->instanceManager->getInstance(CustomPostDeleteMetaInputObjectTypeResolver::class);
            $this->customPostDeleteMetaInputObjectTypeResolver = $customPostDeleteMetaInputObjectTypeResolver;
        }
        return $this->customPostDeleteMetaInputObjectTypeResolver;
    }
    final protected function getCustomPostSetMetaInputObjectTypeResolver(): CustomPostSetMetaInputObjectTypeResolver
    {
        if ($this->customPostSetMetaInputObjectTypeResolver === null) {
            /** @var CustomPostSetMetaInputObjectTypeResolver */
            $customPostSetMetaInputObjectTypeResolver = $this->instanceManager->getInstance(CustomPostSetMetaInputObjectTypeResolver::class);
            $this->customPostSetMetaInputObjectTypeResolver = $customPostSetMetaInputObjectTypeResolver;
        }
        return $this->customPostSetMetaInputObjectTypeResolver;
    }
    final protected function getCustomPostUpdateMetaInputObjectTypeResolver(): CustomPostUpdateMetaInputObjectTypeResolver
    {
        if ($this->customPostUpdateMetaInputObjectTypeResolver === null) {
            /** @var CustomPostUpdateMetaInputObjectTypeResolver */
            $customPostUpdateMetaInputObjectTypeResolver = $this->instanceManager->getInstance(CustomPostUpdateMetaInputObjectTypeResolver::class);
            $this->customPostUpdateMetaInputObjectTypeResolver = $customPostUpdateMetaInputObjectTypeResolver;
        }
        return $this->customPostUpdateMetaInputObjectTypeResolver;
    }
    final protected function getAddCustomPostMetaMutationResolver(): AddCustomPostMetaMutationResolver
    {
        if ($this->addCustomPostMetaMutationResolver === null) {
            /** @var AddCustomPostMetaMutationResolver */
            $addCustomPostMetaMutationResolver = $this->instanceManager->getInstance(AddCustomPostMetaMutationResolver::class);
            $this->addCustomPostMetaMutationResolver = $addCustomPostMetaMutationResolver;
        }
        return $this->addCustomPostMetaMutationResolver;
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
    final protected function getSetCustomPostMetaMutationResolver(): SetCustomPostMetaMutationResolver
    {
        if ($this->setCustomPostMetaMutationResolver === null) {
            /** @var SetCustomPostMetaMutationResolver */
            $setCustomPostMetaMutationResolver = $this->instanceManager->getInstance(SetCustomPostMetaMutationResolver::class);
            $this->setCustomPostMetaMutationResolver = $setCustomPostMetaMutationResolver;
        }
        return $this->setCustomPostMetaMutationResolver;
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
    final protected function getPayloadableDeleteCustomPostMetaMutationResolver(): PayloadableDeleteCustomPostMetaMutationResolver
    {
        if ($this->payloadableDeleteCustomPostMetaMutationResolver === null) {
            /** @var PayloadableDeleteCustomPostMetaMutationResolver */
            $payloadableDeleteCustomPostMetaMutationResolver = $this->instanceManager->getInstance(PayloadableDeleteCustomPostMetaMutationResolver::class);
            $this->payloadableDeleteCustomPostMetaMutationResolver = $payloadableDeleteCustomPostMetaMutationResolver;
        }
        return $this->payloadableDeleteCustomPostMetaMutationResolver;
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
    final protected function getPayloadableUpdateCustomPostMetaMutationResolver(): PayloadableUpdateCustomPostMetaMutationResolver
    {
        if ($this->payloadableUpdateCustomPostMetaMutationResolver === null) {
            /** @var PayloadableUpdateCustomPostMetaMutationResolver */
            $payloadableUpdateCustomPostMetaMutationResolver = $this->instanceManager->getInstance(PayloadableUpdateCustomPostMetaMutationResolver::class);
            $this->payloadableUpdateCustomPostMetaMutationResolver = $payloadableUpdateCustomPostMetaMutationResolver;
        }
        return $this->payloadableUpdateCustomPostMetaMutationResolver;
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
            'addMeta' => $this->__('Add a custom post meta entry', 'custompostmeta-mutations'),
            'deleteMeta' => $this->__('Delete a custom post meta entry', 'custompostmeta-mutations'),
            'setMeta' => $this->__('Set meta entries to a custom post', 'custompostmeta-mutations'),
            'updateMeta' => $this->__('Update a custom post meta entry', 'custompostmeta-mutations'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableCustomPostMetaMutations = $moduleConfiguration->usePayloadableCustomPostMetaMutations();
        if (!$usePayloadableCustomPostMetaMutations) {
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
                'input' => $this->getCustomPostAddMetaInputObjectTypeResolver(),
            ],
            'deleteMeta' => [
                'input' => $this->getCustomPostDeleteMetaInputObjectTypeResolver(),
            ],
            'setMeta' => [
                'input' => $this->getCustomPostSetMetaInputObjectTypeResolver(),
            ],
            'updateMeta' => [
                'input' => $this->getCustomPostUpdateMetaInputObjectTypeResolver(),
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
        $customPost = $object;
        switch ($field->getName()) {
            case 'addMeta':
            case 'deleteMeta':
            case 'setMeta':
            case 'updateMeta':
                $fieldArgsForMutationForObject['input']->{MutationInputProperties::ID} = $objectTypeResolver->getID($customPost);
                break;
        }
        return $fieldArgsForMutationForObject;
    }

    public function getFieldMutationResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?MutationResolverInterface
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableCustomPostMetaMutations = $moduleConfiguration->usePayloadableCustomPostMetaMutations();
        return match ($fieldName) {
            'addMeta' => $usePayloadableCustomPostMetaMutations
                ? $this->getPayloadableAddCustomPostMetaMutationResolver()
                : $this->getAddCustomPostMetaMutationResolver(),
            'updateMeta' => $usePayloadableCustomPostMetaMutations
                ? $this->getPayloadableUpdateCustomPostMetaMutationResolver()
                : $this->getUpdateCustomPostMetaMutationResolver(),
            'deleteMeta' => $usePayloadableCustomPostMetaMutations
                ? $this->getPayloadableDeleteCustomPostMetaMutationResolver()
                : $this->getDeleteCustomPostMetaMutationResolver(),
            'setMeta' => $usePayloadableCustomPostMetaMutations
                ? $this->getPayloadableSetCustomPostMetaMutationResolver()
                : $this->getSetCustomPostMetaMutationResolver(),
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
