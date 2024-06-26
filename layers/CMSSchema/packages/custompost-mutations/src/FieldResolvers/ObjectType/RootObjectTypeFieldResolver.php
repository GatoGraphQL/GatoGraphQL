<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostMutations\Module as CustomPostMutationsModule;
use PoPCMSSchema\CustomPostMutations\ModuleConfiguration as CustomPostMutationsModuleConfiguration;
use PoPCMSSchema\CustomPostMutations\MutationResolvers\CreateGenericCustomPostMutationResolver;
use PoPCMSSchema\CustomPostMutations\MutationResolvers\PayloadableCreateGenericCustomPostMutationResolver;
use PoPCMSSchema\CustomPostMutations\MutationResolvers\PayloadableUpdateGenericCustomPostMutationResolver;
use PoPCMSSchema\CustomPostMutations\MutationResolvers\UpdateGenericCustomPostMutationResolver;
use PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType\RootCreateGenericCustomPostInputObjectTypeResolver;
use PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType\RootUpdateGenericCustomPostInputObjectTypeResolver;
use PoPCMSSchema\CustomPostMutations\TypeResolvers\ObjectType\RootCreateGenericCustomPostMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPostMutations\TypeResolvers\ObjectType\RootUpdateGenericCustomPostMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\GenericCustomPostObjectTypeResolver;
use PoPCMSSchema\UserState\Checkpoints\UserLoggedInCheckpoint;
use PoP\ComponentModel\Checkpoints\CheckpointInterface;
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
    private ?GenericCustomPostObjectTypeResolver $genericCustomPostObjectTypeResolver = null;
    private ?RootUpdateGenericCustomPostMutationPayloadObjectTypeResolver $rootUpdateGenericCustomPostMutationPayloadObjectTypeResolver = null;
    private ?RootCreateGenericCustomPostMutationPayloadObjectTypeResolver $rootCreateGenericCustomPostMutationPayloadObjectTypeResolver = null;
    private ?CreateGenericCustomPostMutationResolver $createGenericCustomPostMutationResolver = null;
    private ?UpdateGenericCustomPostMutationResolver $updateGenericCustomPostMutationResolver = null;
    private ?PayloadableUpdateGenericCustomPostMutationResolver $payloadableUpdateGenericCustomPostMutationResolver = null;
    private ?PayloadableCreateGenericCustomPostMutationResolver $payloadableCreateGenericCustomPostMutationResolver = null;
    private ?RootUpdateGenericCustomPostInputObjectTypeResolver $rootUpdateGenericCustomPostInputObjectTypeResolver = null;
    private ?RootCreateGenericCustomPostInputObjectTypeResolver $rootCreateGenericCustomPostInputObjectTypeResolver = null;
    private ?UserLoggedInCheckpoint $userLoggedInCheckpoint = null;

    final public function setGenericCustomPostObjectTypeResolver(GenericCustomPostObjectTypeResolver $genericCustomPostObjectTypeResolver): void
    {
        $this->genericCustomPostObjectTypeResolver = $genericCustomPostObjectTypeResolver;
    }
    final protected function getGenericCustomPostObjectTypeResolver(): GenericCustomPostObjectTypeResolver
    {
        if ($this->genericCustomPostObjectTypeResolver === null) {
            /** @var GenericCustomPostObjectTypeResolver */
            $genericCustomPostObjectTypeResolver = $this->instanceManager->getInstance(GenericCustomPostObjectTypeResolver::class);
            $this->genericCustomPostObjectTypeResolver = $genericCustomPostObjectTypeResolver;
        }
        return $this->genericCustomPostObjectTypeResolver;
    }
    final public function setRootUpdateGenericCustomPostMutationPayloadObjectTypeResolver(RootUpdateGenericCustomPostMutationPayloadObjectTypeResolver $rootUpdateGenericCustomPostMutationPayloadObjectTypeResolver): void
    {
        $this->rootUpdateGenericCustomPostMutationPayloadObjectTypeResolver = $rootUpdateGenericCustomPostMutationPayloadObjectTypeResolver;
    }
    final protected function getRootUpdateGenericCustomPostMutationPayloadObjectTypeResolver(): RootUpdateGenericCustomPostMutationPayloadObjectTypeResolver
    {
        if ($this->rootUpdateGenericCustomPostMutationPayloadObjectTypeResolver === null) {
            /** @var RootUpdateGenericCustomPostMutationPayloadObjectTypeResolver */
            $rootUpdateGenericCustomPostMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootUpdateGenericCustomPostMutationPayloadObjectTypeResolver::class);
            $this->rootUpdateGenericCustomPostMutationPayloadObjectTypeResolver = $rootUpdateGenericCustomPostMutationPayloadObjectTypeResolver;
        }
        return $this->rootUpdateGenericCustomPostMutationPayloadObjectTypeResolver;
    }
    final public function setRootCreateGenericCustomPostMutationPayloadObjectTypeResolver(RootCreateGenericCustomPostMutationPayloadObjectTypeResolver $rootCreateGenericCustomPostMutationPayloadObjectTypeResolver): void
    {
        $this->rootCreateGenericCustomPostMutationPayloadObjectTypeResolver = $rootCreateGenericCustomPostMutationPayloadObjectTypeResolver;
    }
    final protected function getRootCreateGenericCustomPostMutationPayloadObjectTypeResolver(): RootCreateGenericCustomPostMutationPayloadObjectTypeResolver
    {
        if ($this->rootCreateGenericCustomPostMutationPayloadObjectTypeResolver === null) {
            /** @var RootCreateGenericCustomPostMutationPayloadObjectTypeResolver */
            $rootCreateGenericCustomPostMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootCreateGenericCustomPostMutationPayloadObjectTypeResolver::class);
            $this->rootCreateGenericCustomPostMutationPayloadObjectTypeResolver = $rootCreateGenericCustomPostMutationPayloadObjectTypeResolver;
        }
        return $this->rootCreateGenericCustomPostMutationPayloadObjectTypeResolver;
    }
    final public function setCreateGenericCustomPostMutationResolver(CreateGenericCustomPostMutationResolver $createGenericCustomPostMutationResolver): void
    {
        $this->createGenericCustomPostMutationResolver = $createGenericCustomPostMutationResolver;
    }
    final protected function getCreateGenericCustomPostMutationResolver(): CreateGenericCustomPostMutationResolver
    {
        if ($this->createGenericCustomPostMutationResolver === null) {
            /** @var CreateGenericCustomPostMutationResolver */
            $createGenericCustomPostMutationResolver = $this->instanceManager->getInstance(CreateGenericCustomPostMutationResolver::class);
            $this->createGenericCustomPostMutationResolver = $createGenericCustomPostMutationResolver;
        }
        return $this->createGenericCustomPostMutationResolver;
    }
    final public function setUpdateGenericCustomPostMutationResolver(UpdateGenericCustomPostMutationResolver $updateGenericCustomPostMutationResolver): void
    {
        $this->updateGenericCustomPostMutationResolver = $updateGenericCustomPostMutationResolver;
    }
    final protected function getUpdateGenericCustomPostMutationResolver(): UpdateGenericCustomPostMutationResolver
    {
        if ($this->updateGenericCustomPostMutationResolver === null) {
            /** @var UpdateGenericCustomPostMutationResolver */
            $updateGenericCustomPostMutationResolver = $this->instanceManager->getInstance(UpdateGenericCustomPostMutationResolver::class);
            $this->updateGenericCustomPostMutationResolver = $updateGenericCustomPostMutationResolver;
        }
        return $this->updateGenericCustomPostMutationResolver;
    }
    final public function setPayloadableUpdateGenericCustomPostMutationResolver(PayloadableUpdateGenericCustomPostMutationResolver $payloadableUpdateGenericCustomPostMutationResolver): void
    {
        $this->payloadableUpdateGenericCustomPostMutationResolver = $payloadableUpdateGenericCustomPostMutationResolver;
    }
    final protected function getPayloadableUpdateGenericCustomPostMutationResolver(): PayloadableUpdateGenericCustomPostMutationResolver
    {
        if ($this->payloadableUpdateGenericCustomPostMutationResolver === null) {
            /** @var PayloadableUpdateGenericCustomPostMutationResolver */
            $payloadableUpdateGenericCustomPostMutationResolver = $this->instanceManager->getInstance(PayloadableUpdateGenericCustomPostMutationResolver::class);
            $this->payloadableUpdateGenericCustomPostMutationResolver = $payloadableUpdateGenericCustomPostMutationResolver;
        }
        return $this->payloadableUpdateGenericCustomPostMutationResolver;
    }
    final public function setPayloadableCreateGenericCustomPostMutationResolver(PayloadableCreateGenericCustomPostMutationResolver $payloadableCreateGenericCustomPostMutationResolver): void
    {
        $this->payloadableCreateGenericCustomPostMutationResolver = $payloadableCreateGenericCustomPostMutationResolver;
    }
    final protected function getPayloadableCreateGenericCustomPostMutationResolver(): PayloadableCreateGenericCustomPostMutationResolver
    {
        if ($this->payloadableCreateGenericCustomPostMutationResolver === null) {
            /** @var PayloadableCreateGenericCustomPostMutationResolver */
            $payloadableCreateGenericCustomPostMutationResolver = $this->instanceManager->getInstance(PayloadableCreateGenericCustomPostMutationResolver::class);
            $this->payloadableCreateGenericCustomPostMutationResolver = $payloadableCreateGenericCustomPostMutationResolver;
        }
        return $this->payloadableCreateGenericCustomPostMutationResolver;
    }
    final public function setRootUpdateGenericCustomPostInputObjectTypeResolver(RootUpdateGenericCustomPostInputObjectTypeResolver $rootUpdateGenericCustomPostInputObjectTypeResolver): void
    {
        $this->rootUpdateGenericCustomPostInputObjectTypeResolver = $rootUpdateGenericCustomPostInputObjectTypeResolver;
    }
    final protected function getRootUpdateGenericCustomPostInputObjectTypeResolver(): RootUpdateGenericCustomPostInputObjectTypeResolver
    {
        if ($this->rootUpdateGenericCustomPostInputObjectTypeResolver === null) {
            /** @var RootUpdateGenericCustomPostInputObjectTypeResolver */
            $rootUpdateGenericCustomPostInputObjectTypeResolver = $this->instanceManager->getInstance(RootUpdateGenericCustomPostInputObjectTypeResolver::class);
            $this->rootUpdateGenericCustomPostInputObjectTypeResolver = $rootUpdateGenericCustomPostInputObjectTypeResolver;
        }
        return $this->rootUpdateGenericCustomPostInputObjectTypeResolver;
    }
    final public function setRootCreateGenericCustomPostInputObjectTypeResolver(RootCreateGenericCustomPostInputObjectTypeResolver $rootCreateGenericCustomPostInputObjectTypeResolver): void
    {
        $this->rootCreateGenericCustomPostInputObjectTypeResolver = $rootCreateGenericCustomPostInputObjectTypeResolver;
    }
    final protected function getRootCreateGenericCustomPostInputObjectTypeResolver(): RootCreateGenericCustomPostInputObjectTypeResolver
    {
        if ($this->rootCreateGenericCustomPostInputObjectTypeResolver === null) {
            /** @var RootCreateGenericCustomPostInputObjectTypeResolver */
            $rootCreateGenericCustomPostInputObjectTypeResolver = $this->instanceManager->getInstance(RootCreateGenericCustomPostInputObjectTypeResolver::class);
            $this->rootCreateGenericCustomPostInputObjectTypeResolver = $rootCreateGenericCustomPostInputObjectTypeResolver;
        }
        return $this->rootCreateGenericCustomPostInputObjectTypeResolver;
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
            RootObjectTypeResolver::class,
        ];
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        /** @var EngineModuleConfiguration */
        $moduleConfiguration = App::getModule(EngineModule::class)->getConfiguration();
        return array_merge(
            [
                'createCustomPost',
            ],
            !$moduleConfiguration->disableRedundantRootTypeMutationFields() ? [
                'updateCustomPost',
            ] : []
        );
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'createCustomPost' => $this->__('Create a custom post. This mutation accepts the data that is common to all custom posts (title, content, excerpt, slug, etc), but no custom data (such as the price of a Product CPT). So use it with care, only for those custom post types that do not require to be provided data for their own custom fields (for those, you will need to use a more specific mutation, for that CPT)', 'custompost-mutations'),
            'updateCustomPost' => $this->__('Update a custom post', 'custompost-mutations'),
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
            'createCustomPost',
            'updateCustomPost' =>
                SchemaTypeModifiers::NON_NULLABLE,
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
            'createCustomPost' => [
                'input' => $this->getRootCreateGenericCustomPostInputObjectTypeResolver(),
            ],
            'updateCustomPost' => [
                'input' => $this->getRootUpdateGenericCustomPostInputObjectTypeResolver(),
            ],
            default => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return match ($fieldArgName) {
            'input' => SchemaTypeModifiers::MANDATORY,
            default => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldMutationResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?MutationResolverInterface
    {
        /** @var CustomPostMutationsModuleConfiguration */
        $moduleConfiguration = App::getModule(CustomPostMutationsModule::class)->getConfiguration();
        $usePayloadableCustomPostMutations = $moduleConfiguration->usePayloadableCustomPostMutations();
        return match ($fieldName) {
            'createCustomPost' => $usePayloadableCustomPostMutations
                ? $this->getPayloadableCreateGenericCustomPostMutationResolver()
                : $this->getCreateGenericCustomPostMutationResolver(),
            'updateCustomPost' => $usePayloadableCustomPostMutations
                ? $this->getPayloadableUpdateGenericCustomPostMutationResolver()
                : $this->getUpdateGenericCustomPostMutationResolver(),
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
                'createCustomPost' => $this->getRootCreateGenericCustomPostMutationPayloadObjectTypeResolver(),
                'updateCustomPost' => $this->getRootUpdateGenericCustomPostMutationPayloadObjectTypeResolver(),
                default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
            };
        }
        return match ($fieldName) {
            'createCustomPost',
            'updateCustomPost'
                => $this->getGenericCustomPostObjectTypeResolver(),
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
            case 'createCustomPost':
            case 'updateCustomPost':
                $validationCheckpoints[] = $this->getUserLoggedInCheckpoint();
                break;
        }
        return $validationCheckpoints;
    }
}
