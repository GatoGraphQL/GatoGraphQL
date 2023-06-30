<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMediaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostMediaMutations\Module;
use PoPCMSSchema\CustomPostMediaMutations\ModuleConfiguration;
use PoPCMSSchema\CustomPostMediaMutations\MutationResolvers\PayloadableRemoveFeaturedImageFromCustomPostMutationResolver;
use PoPCMSSchema\CustomPostMediaMutations\MutationResolvers\PayloadableSetFeaturedImageOnCustomPostMutationResolver;
use PoPCMSSchema\CustomPostMediaMutations\MutationResolvers\RemoveFeaturedImageFromCustomPostMutationResolver;
use PoPCMSSchema\CustomPostMediaMutations\MutationResolvers\SetFeaturedImageOnCustomPostMutationResolver;
use PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\InputObjectType\RootRemoveFeaturedImageFromCustomPostInputObjectTypeResolver;
use PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\InputObjectType\RootSetFeaturedImageOnCustomPostInputObjectTypeResolver;
use PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\ObjectType\RootRemoveFeaturedImageFromCustomPostMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\ObjectType\RootSetFeaturedImageOnCustomPostMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\UnionType\CustomPostUnionTypeResolver;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\Module as EngineModule;
use PoP\Engine\ModuleConfiguration as EngineModuleConfiguration;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\Root\App;

class RootObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    private ?CustomPostUnionTypeResolver $customPostUnionTypeResolver = null;
    private ?SetFeaturedImageOnCustomPostMutationResolver $setFeaturedImageOnCustomPostMutationResolver = null;
    private ?RemoveFeaturedImageFromCustomPostMutationResolver $removeFeaturedImageFromCustomPostMutationResolver = null;
    private ?RootSetFeaturedImageOnCustomPostInputObjectTypeResolver $rootSetFeaturedImageOnCustomPostInputObjectTypeResolver = null;
    private ?RootRemoveFeaturedImageFromCustomPostInputObjectTypeResolver $rootRemoveFeaturedImageFromCustomPostInputObjectTypeResolver = null;
    private ?PayloadableSetFeaturedImageOnCustomPostMutationResolver $payloadableSetFeaturedImageOnCustomPostMutationResolver = null;
    private ?PayloadableRemoveFeaturedImageFromCustomPostMutationResolver $payloadableRemoveFeaturedImageFromCustomPostMutationResolver = null;
    private ?RootSetFeaturedImageOnCustomPostMutationPayloadObjectTypeResolver $rootSetFeaturedImageOnCustomPostMutationPayloadObjectTypeResolver = null;
    private ?RootRemoveFeaturedImageFromCustomPostMutationPayloadObjectTypeResolver $rootRemoveFeaturedImageFromCustomPostMutationPayloadObjectTypeResolver = null;

    final public function setCustomPostUnionTypeResolver(CustomPostUnionTypeResolver $customPostUnionTypeResolver): void
    {
        $this->customPostUnionTypeResolver = $customPostUnionTypeResolver;
    }
    final protected function getCustomPostUnionTypeResolver(): CustomPostUnionTypeResolver
    {
        if ($this->customPostUnionTypeResolver === null) {
            /** @var CustomPostUnionTypeResolver */
            $customPostUnionTypeResolver = $this->instanceManager->getInstance(CustomPostUnionTypeResolver::class);
            $this->customPostUnionTypeResolver = $customPostUnionTypeResolver;
        }
        return $this->customPostUnionTypeResolver;
    }
    final public function setSetFeaturedImageOnCustomPostMutationResolver(SetFeaturedImageOnCustomPostMutationResolver $setFeaturedImageOnCustomPostMutationResolver): void
    {
        $this->setFeaturedImageOnCustomPostMutationResolver = $setFeaturedImageOnCustomPostMutationResolver;
    }
    final protected function getSetFeaturedImageOnCustomPostMutationResolver(): SetFeaturedImageOnCustomPostMutationResolver
    {
        if ($this->setFeaturedImageOnCustomPostMutationResolver === null) {
            /** @var SetFeaturedImageOnCustomPostMutationResolver */
            $setFeaturedImageOnCustomPostMutationResolver = $this->instanceManager->getInstance(SetFeaturedImageOnCustomPostMutationResolver::class);
            $this->setFeaturedImageOnCustomPostMutationResolver = $setFeaturedImageOnCustomPostMutationResolver;
        }
        return $this->setFeaturedImageOnCustomPostMutationResolver;
    }
    final public function setRemoveFeaturedImageFromCustomPostMutationResolver(RemoveFeaturedImageFromCustomPostMutationResolver $removeFeaturedImageFromCustomPostMutationResolver): void
    {
        $this->removeFeaturedImageFromCustomPostMutationResolver = $removeFeaturedImageFromCustomPostMutationResolver;
    }
    final protected function getRemoveFeaturedImageFromCustomPostMutationResolver(): RemoveFeaturedImageFromCustomPostMutationResolver
    {
        if ($this->removeFeaturedImageFromCustomPostMutationResolver === null) {
            /** @var RemoveFeaturedImageFromCustomPostMutationResolver */
            $removeFeaturedImageFromCustomPostMutationResolver = $this->instanceManager->getInstance(RemoveFeaturedImageFromCustomPostMutationResolver::class);
            $this->removeFeaturedImageFromCustomPostMutationResolver = $removeFeaturedImageFromCustomPostMutationResolver;
        }
        return $this->removeFeaturedImageFromCustomPostMutationResolver;
    }
    final public function setRootSetFeaturedImageOnCustomPostInputObjectTypeResolver(RootSetFeaturedImageOnCustomPostInputObjectTypeResolver $rootSetFeaturedImageOnCustomPostInputObjectTypeResolver): void
    {
        $this->rootSetFeaturedImageOnCustomPostInputObjectTypeResolver = $rootSetFeaturedImageOnCustomPostInputObjectTypeResolver;
    }
    final protected function getRootSetFeaturedImageOnCustomPostInputObjectTypeResolver(): RootSetFeaturedImageOnCustomPostInputObjectTypeResolver
    {
        if ($this->rootSetFeaturedImageOnCustomPostInputObjectTypeResolver === null) {
            /** @var RootSetFeaturedImageOnCustomPostInputObjectTypeResolver */
            $rootSetFeaturedImageOnCustomPostInputObjectTypeResolver = $this->instanceManager->getInstance(RootSetFeaturedImageOnCustomPostInputObjectTypeResolver::class);
            $this->rootSetFeaturedImageOnCustomPostInputObjectTypeResolver = $rootSetFeaturedImageOnCustomPostInputObjectTypeResolver;
        }
        return $this->rootSetFeaturedImageOnCustomPostInputObjectTypeResolver;
    }
    final public function setRootRemoveFeaturedImageFromCustomPostInputObjectTypeResolver(RootRemoveFeaturedImageFromCustomPostInputObjectTypeResolver $rootRemoveFeaturedImageFromCustomPostInputObjectTypeResolver): void
    {
        $this->rootRemoveFeaturedImageFromCustomPostInputObjectTypeResolver = $rootRemoveFeaturedImageFromCustomPostInputObjectTypeResolver;
    }
    final protected function getRootRemoveFeaturedImageFromCustomPostInputObjectTypeResolver(): RootRemoveFeaturedImageFromCustomPostInputObjectTypeResolver
    {
        if ($this->rootRemoveFeaturedImageFromCustomPostInputObjectTypeResolver === null) {
            /** @var RootRemoveFeaturedImageFromCustomPostInputObjectTypeResolver */
            $rootRemoveFeaturedImageFromCustomPostInputObjectTypeResolver = $this->instanceManager->getInstance(RootRemoveFeaturedImageFromCustomPostInputObjectTypeResolver::class);
            $this->rootRemoveFeaturedImageFromCustomPostInputObjectTypeResolver = $rootRemoveFeaturedImageFromCustomPostInputObjectTypeResolver;
        }
        return $this->rootRemoveFeaturedImageFromCustomPostInputObjectTypeResolver;
    }
    final public function setPayloadableSetFeaturedImageOnCustomPostMutationResolver(PayloadableSetFeaturedImageOnCustomPostMutationResolver $payloadableSetFeaturedImageOnCustomPostMutationResolver): void
    {
        $this->payloadableSetFeaturedImageOnCustomPostMutationResolver = $payloadableSetFeaturedImageOnCustomPostMutationResolver;
    }
    final protected function getPayloadableSetFeaturedImageOnCustomPostMutationResolver(): PayloadableSetFeaturedImageOnCustomPostMutationResolver
    {
        if ($this->payloadableSetFeaturedImageOnCustomPostMutationResolver === null) {
            /** @var PayloadableSetFeaturedImageOnCustomPostMutationResolver */
            $payloadableSetFeaturedImageOnCustomPostMutationResolver = $this->instanceManager->getInstance(PayloadableSetFeaturedImageOnCustomPostMutationResolver::class);
            $this->payloadableSetFeaturedImageOnCustomPostMutationResolver = $payloadableSetFeaturedImageOnCustomPostMutationResolver;
        }
        return $this->payloadableSetFeaturedImageOnCustomPostMutationResolver;
    }
    final public function setPayloadableRemoveFeaturedImageFromCustomPostMutationResolver(PayloadableRemoveFeaturedImageFromCustomPostMutationResolver $payloadableRemoveFeaturedImageFromCustomPostMutationResolver): void
    {
        $this->payloadableRemoveFeaturedImageFromCustomPostMutationResolver = $payloadableRemoveFeaturedImageFromCustomPostMutationResolver;
    }
    final protected function getPayloadableRemoveFeaturedImageFromCustomPostMutationResolver(): PayloadableRemoveFeaturedImageFromCustomPostMutationResolver
    {
        if ($this->payloadableRemoveFeaturedImageFromCustomPostMutationResolver === null) {
            /** @var PayloadableRemoveFeaturedImageFromCustomPostMutationResolver */
            $payloadableRemoveFeaturedImageFromCustomPostMutationResolver = $this->instanceManager->getInstance(PayloadableRemoveFeaturedImageFromCustomPostMutationResolver::class);
            $this->payloadableRemoveFeaturedImageFromCustomPostMutationResolver = $payloadableRemoveFeaturedImageFromCustomPostMutationResolver;
        }
        return $this->payloadableRemoveFeaturedImageFromCustomPostMutationResolver;
    }
    final public function setRootSetFeaturedImageOnCustomPostMutationPayloadObjectTypeResolver(RootSetFeaturedImageOnCustomPostMutationPayloadObjectTypeResolver $rootSetFeaturedImageOnCustomPostMutationPayloadObjectTypeResolver): void
    {
        $this->rootSetFeaturedImageOnCustomPostMutationPayloadObjectTypeResolver = $rootSetFeaturedImageOnCustomPostMutationPayloadObjectTypeResolver;
    }
    final protected function getRootSetFeaturedImageOnCustomPostMutationPayloadObjectTypeResolver(): RootSetFeaturedImageOnCustomPostMutationPayloadObjectTypeResolver
    {
        if ($this->rootSetFeaturedImageOnCustomPostMutationPayloadObjectTypeResolver === null) {
            /** @var RootSetFeaturedImageOnCustomPostMutationPayloadObjectTypeResolver */
            $rootSetFeaturedImageOnCustomPostMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootSetFeaturedImageOnCustomPostMutationPayloadObjectTypeResolver::class);
            $this->rootSetFeaturedImageOnCustomPostMutationPayloadObjectTypeResolver = $rootSetFeaturedImageOnCustomPostMutationPayloadObjectTypeResolver;
        }
        return $this->rootSetFeaturedImageOnCustomPostMutationPayloadObjectTypeResolver;
    }
    final public function setRootRemoveFeaturedImageFromCustomPostMutationPayloadObjectTypeResolver(RootRemoveFeaturedImageFromCustomPostMutationPayloadObjectTypeResolver $rootRemoveFeaturedImageFromCustomPostMutationPayloadObjectTypeResolver): void
    {
        $this->rootRemoveFeaturedImageFromCustomPostMutationPayloadObjectTypeResolver = $rootRemoveFeaturedImageFromCustomPostMutationPayloadObjectTypeResolver;
    }
    final protected function getRootRemoveFeaturedImageFromCustomPostMutationPayloadObjectTypeResolver(): RootRemoveFeaturedImageFromCustomPostMutationPayloadObjectTypeResolver
    {
        if ($this->rootRemoveFeaturedImageFromCustomPostMutationPayloadObjectTypeResolver === null) {
            /** @var RootRemoveFeaturedImageFromCustomPostMutationPayloadObjectTypeResolver */
            $rootRemoveFeaturedImageFromCustomPostMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootRemoveFeaturedImageFromCustomPostMutationPayloadObjectTypeResolver::class);
            $this->rootRemoveFeaturedImageFromCustomPostMutationPayloadObjectTypeResolver = $rootRemoveFeaturedImageFromCustomPostMutationPayloadObjectTypeResolver;
        }
        return $this->rootRemoveFeaturedImageFromCustomPostMutationPayloadObjectTypeResolver;
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
        if ($moduleConfiguration->disableRedundantRootTypeMutationFields()) {
            return [];
        }
        return [
            'setFeaturedImageOnCustomPost',
            'removeFeaturedImageFromCustomPost',
        ];
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'setFeaturedImageOnCustomPost' => $this->__('Set the featured image on a custom post', 'custompostmedia-mutations'),
            'removeFeaturedImageFromCustomPost' => $this->__('Remove the featured image from a custom post', 'custompostmedia-mutations'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableCustomPostMediaMutations = $moduleConfiguration->usePayloadableCustomPostMediaMutations();
        if (!$usePayloadableCustomPostMediaMutations) {
            return parent::getFieldTypeModifiers($objectTypeResolver, $fieldName);
        }
        return match ($fieldName) {
            'setFeaturedImageOnCustomPost',
            'removeFeaturedImageFromCustomPost'
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
            'setFeaturedImageOnCustomPost' => [
                'input' => $this->getRootSetFeaturedImageOnCustomPostInputObjectTypeResolver(),
            ],
            'removeFeaturedImageFromCustomPost' => [
                'input' => $this->getRootRemoveFeaturedImageFromCustomPostInputObjectTypeResolver(),
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
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableCustomPostMediaMutations = $moduleConfiguration->usePayloadableCustomPostMediaMutations();
        return match ($fieldName) {
            'setFeaturedImageOnCustomPost' => $usePayloadableCustomPostMediaMutations
                ? $this->getPayloadableSetFeaturedImageOnCustomPostMutationResolver()
                : $this->getSetFeaturedImageOnCustomPostMutationResolver(),
            'removeFeaturedImageFromCustomPost' => $usePayloadableCustomPostMediaMutations
                ? $this->getPayloadableRemoveFeaturedImageFromCustomPostMutationResolver()
                : $this->getRemoveFeaturedImageFromCustomPostMutationResolver(),
            default => parent::getFieldMutationResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableCustomPostMediaMutations = $moduleConfiguration->usePayloadableCustomPostMediaMutations();
        if ($usePayloadableCustomPostMediaMutations) {
            return match ($fieldName) {
                'setFeaturedImageOnCustomPost' => $this->getRootSetFeaturedImageOnCustomPostMutationPayloadObjectTypeResolver(),
                'removeFeaturedImageFromCustomPost' => $this->getRootRemoveFeaturedImageFromCustomPostMutationPayloadObjectTypeResolver(),
                default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
            };
        }
        return match ($fieldName) {
            'setFeaturedImageOnCustomPost',
            'removeFeaturedImageFromCustomPost'
                => $this->getCustomPostUnionTypeResolver(),
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
