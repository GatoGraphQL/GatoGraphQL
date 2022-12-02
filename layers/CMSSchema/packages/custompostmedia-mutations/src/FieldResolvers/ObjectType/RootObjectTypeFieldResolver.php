<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMediaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostMediaMutations\Module;
use PoPCMSSchema\CustomPostMediaMutations\ModuleConfiguration;
use PoPCMSSchema\CustomPostMediaMutations\MutationResolvers\PayloadableRemoveFeaturedImageFromCustomPostMutationResolver;
use PoPCMSSchema\CustomPostMediaMutations\MutationResolvers\PayloadableSetFeaturedImageOnCustomPostMutationResolver;
use PoPCMSSchema\CustomPostMediaMutations\MutationResolvers\RemoveFeaturedImageFromCustomPostMutationResolver;
use PoPCMSSchema\CustomPostMediaMutations\MutationResolvers\SetFeaturedImageOnCustomPostMutationResolver;
use PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\InputObjectType\RootRemoveFeaturedImageFromCustomPostFilterInputObjectTypeResolver;
use PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\InputObjectType\RootSetFeaturedImageOnCustomPostFilterInputObjectTypeResolver;
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
    private ?RootSetFeaturedImageOnCustomPostFilterInputObjectTypeResolver $rootSetFeaturedImageOnCustomPostFilterInputObjectTypeResolver = null;
    private ?RootRemoveFeaturedImageFromCustomPostFilterInputObjectTypeResolver $rootRemoveFeaturedImageFromCustomPostFilterInputObjectTypeResolver = null;
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
        /** @var CustomPostUnionTypeResolver */
        return $this->customPostUnionTypeResolver ??= $this->instanceManager->getInstance(CustomPostUnionTypeResolver::class);
    }
    final public function setSetFeaturedImageOnCustomPostMutationResolver(SetFeaturedImageOnCustomPostMutationResolver $setFeaturedImageOnCustomPostMutationResolver): void
    {
        $this->setFeaturedImageOnCustomPostMutationResolver = $setFeaturedImageOnCustomPostMutationResolver;
    }
    final protected function getSetFeaturedImageOnCustomPostMutationResolver(): SetFeaturedImageOnCustomPostMutationResolver
    {
        /** @var SetFeaturedImageOnCustomPostMutationResolver */
        return $this->setFeaturedImageOnCustomPostMutationResolver ??= $this->instanceManager->getInstance(SetFeaturedImageOnCustomPostMutationResolver::class);
    }
    final public function setRemoveFeaturedImageFromCustomPostMutationResolver(RemoveFeaturedImageFromCustomPostMutationResolver $removeFeaturedImageFromCustomPostMutationResolver): void
    {
        $this->removeFeaturedImageFromCustomPostMutationResolver = $removeFeaturedImageFromCustomPostMutationResolver;
    }
    final protected function getRemoveFeaturedImageFromCustomPostMutationResolver(): RemoveFeaturedImageFromCustomPostMutationResolver
    {
        /** @var RemoveFeaturedImageFromCustomPostMutationResolver */
        return $this->removeFeaturedImageFromCustomPostMutationResolver ??= $this->instanceManager->getInstance(RemoveFeaturedImageFromCustomPostMutationResolver::class);
    }
    final public function setRootSetFeaturedImageOnCustomPostFilterInputObjectTypeResolver(RootSetFeaturedImageOnCustomPostFilterInputObjectTypeResolver $rootSetFeaturedImageOnCustomPostFilterInputObjectTypeResolver): void
    {
        $this->rootSetFeaturedImageOnCustomPostFilterInputObjectTypeResolver = $rootSetFeaturedImageOnCustomPostFilterInputObjectTypeResolver;
    }
    final protected function getRootSetFeaturedImageOnCustomPostFilterInputObjectTypeResolver(): RootSetFeaturedImageOnCustomPostFilterInputObjectTypeResolver
    {
        /** @var RootSetFeaturedImageOnCustomPostFilterInputObjectTypeResolver */
        return $this->rootSetFeaturedImageOnCustomPostFilterInputObjectTypeResolver ??= $this->instanceManager->getInstance(RootSetFeaturedImageOnCustomPostFilterInputObjectTypeResolver::class);
    }
    final public function setRootRemoveFeaturedImageFromCustomPostFilterInputObjectTypeResolver(RootRemoveFeaturedImageFromCustomPostFilterInputObjectTypeResolver $rootRemoveFeaturedImageFromCustomPostFilterInputObjectTypeResolver): void
    {
        $this->rootRemoveFeaturedImageFromCustomPostFilterInputObjectTypeResolver = $rootRemoveFeaturedImageFromCustomPostFilterInputObjectTypeResolver;
    }
    final protected function getRootRemoveFeaturedImageFromCustomPostFilterInputObjectTypeResolver(): RootRemoveFeaturedImageFromCustomPostFilterInputObjectTypeResolver
    {
        /** @var RootRemoveFeaturedImageFromCustomPostFilterInputObjectTypeResolver */
        return $this->rootRemoveFeaturedImageFromCustomPostFilterInputObjectTypeResolver ??= $this->instanceManager->getInstance(RootRemoveFeaturedImageFromCustomPostFilterInputObjectTypeResolver::class);
    }
    final public function setPayloadableSetFeaturedImageOnCustomPostMutationResolver(PayloadableSetFeaturedImageOnCustomPostMutationResolver $payloadableSetFeaturedImageOnCustomPostMutationResolver): void
    {
        $this->payloadableSetFeaturedImageOnCustomPostMutationResolver = $payloadableSetFeaturedImageOnCustomPostMutationResolver;
    }
    final protected function getPayloadableSetFeaturedImageOnCustomPostMutationResolver(): PayloadableSetFeaturedImageOnCustomPostMutationResolver
    {
        /** @var PayloadableSetFeaturedImageOnCustomPostMutationResolver */
        return $this->payloadableSetFeaturedImageOnCustomPostMutationResolver ??= $this->instanceManager->getInstance(PayloadableSetFeaturedImageOnCustomPostMutationResolver::class);
    }
    final public function setPayloadableRemoveFeaturedImageFromCustomPostMutationResolver(PayloadableRemoveFeaturedImageFromCustomPostMutationResolver $payloadableRemoveFeaturedImageFromCustomPostMutationResolver): void
    {
        $this->payloadableRemoveFeaturedImageFromCustomPostMutationResolver = $payloadableRemoveFeaturedImageFromCustomPostMutationResolver;
    }
    final protected function getPayloadableRemoveFeaturedImageFromCustomPostMutationResolver(): PayloadableRemoveFeaturedImageFromCustomPostMutationResolver
    {
        /** @var PayloadableRemoveFeaturedImageFromCustomPostMutationResolver */
        return $this->payloadableRemoveFeaturedImageFromCustomPostMutationResolver ??= $this->instanceManager->getInstance(PayloadableRemoveFeaturedImageFromCustomPostMutationResolver::class);
    }
    final public function setRootSetFeaturedImageOnCustomPostMutationPayloadObjectTypeResolver(RootSetFeaturedImageOnCustomPostMutationPayloadObjectTypeResolver $rootSetFeaturedImageOnCustomPostMutationPayloadObjectTypeResolver): void
    {
        $this->rootSetFeaturedImageOnCustomPostMutationPayloadObjectTypeResolver = $rootSetFeaturedImageOnCustomPostMutationPayloadObjectTypeResolver;
    }
    final protected function getRootSetFeaturedImageOnCustomPostMutationPayloadObjectTypeResolver(): RootSetFeaturedImageOnCustomPostMutationPayloadObjectTypeResolver
    {
        /** @var RootSetFeaturedImageOnCustomPostMutationPayloadObjectTypeResolver */
        return $this->rootSetFeaturedImageOnCustomPostMutationPayloadObjectTypeResolver ??= $this->instanceManager->getInstance(RootSetFeaturedImageOnCustomPostMutationPayloadObjectTypeResolver::class);
    }
    final public function setRootRemoveFeaturedImageFromCustomPostMutationPayloadObjectTypeResolver(RootRemoveFeaturedImageFromCustomPostMutationPayloadObjectTypeResolver $rootRemoveFeaturedImageFromCustomPostMutationPayloadObjectTypeResolver): void
    {
        $this->rootRemoveFeaturedImageFromCustomPostMutationPayloadObjectTypeResolver = $rootRemoveFeaturedImageFromCustomPostMutationPayloadObjectTypeResolver;
    }
    final protected function getRootRemoveFeaturedImageFromCustomPostMutationPayloadObjectTypeResolver(): RootRemoveFeaturedImageFromCustomPostMutationPayloadObjectTypeResolver
    {
        /** @var RootRemoveFeaturedImageFromCustomPostMutationPayloadObjectTypeResolver */
        return $this->rootRemoveFeaturedImageFromCustomPostMutationPayloadObjectTypeResolver ??= $this->instanceManager->getInstance(RootRemoveFeaturedImageFromCustomPostMutationPayloadObjectTypeResolver::class);
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
                'input' => $this->getRootSetFeaturedImageOnCustomPostFilterInputObjectTypeResolver(),
            ],
            'removeFeaturedImageFromCustomPost' => [
                'input' => $this->getRootRemoveFeaturedImageFromCustomPostFilterInputObjectTypeResolver(),
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
