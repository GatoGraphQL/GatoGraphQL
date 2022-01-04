<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMediaMutations\FieldResolvers\ObjectType;

use PoP\Root\Managers\ComponentManager;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\Component as EngineComponent;
use PoP\Engine\ComponentConfiguration as EngineComponentConfiguration;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoPSchema\CustomPostMediaMutations\MutationResolvers\RemoveFeaturedImageOnCustomPostMutationResolver;
use PoPSchema\CustomPostMediaMutations\MutationResolvers\SetFeaturedImageOnCustomPostMutationResolver;
use PoPSchema\CustomPostMediaMutations\TypeResolvers\InputObjectType\RootRemoveFeaturedImageFromCustomPostFilterInputObjectTypeResolver;
use PoPSchema\CustomPostMediaMutations\TypeResolvers\InputObjectType\RootSetFeaturedImageOnCustomPostFilterInputObjectTypeResolver;
use PoPSchema\CustomPosts\TypeResolvers\UnionType\CustomPostUnionTypeResolver;

class RootObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    private ?CustomPostUnionTypeResolver $customPostUnionTypeResolver = null;
    private ?SetFeaturedImageOnCustomPostMutationResolver $setFeaturedImageOnCustomPostMutationResolver = null;
    private ?RemoveFeaturedImageOnCustomPostMutationResolver $removeFeaturedImageOnCustomPostMutationResolver = null;
    private ?RootSetFeaturedImageOnCustomPostFilterInputObjectTypeResolver $rootSetFeaturedImageOnCustomPostFilterInputObjectTypeResolver = null;
    private ?RootRemoveFeaturedImageFromCustomPostFilterInputObjectTypeResolver $rootRemoveFeaturedImageFromCustomPostFilterInputObjectTypeResolver = null;

    final public function setCustomPostUnionTypeResolver(CustomPostUnionTypeResolver $customPostUnionTypeResolver): void
    {
        $this->customPostUnionTypeResolver = $customPostUnionTypeResolver;
    }
    final protected function getCustomPostUnionTypeResolver(): CustomPostUnionTypeResolver
    {
        return $this->customPostUnionTypeResolver ??= $this->instanceManager->getInstance(CustomPostUnionTypeResolver::class);
    }
    final public function setSetFeaturedImageOnCustomPostMutationResolver(SetFeaturedImageOnCustomPostMutationResolver $setFeaturedImageOnCustomPostMutationResolver): void
    {
        $this->setFeaturedImageOnCustomPostMutationResolver = $setFeaturedImageOnCustomPostMutationResolver;
    }
    final protected function getSetFeaturedImageOnCustomPostMutationResolver(): SetFeaturedImageOnCustomPostMutationResolver
    {
        return $this->setFeaturedImageOnCustomPostMutationResolver ??= $this->instanceManager->getInstance(SetFeaturedImageOnCustomPostMutationResolver::class);
    }
    final public function setRemoveFeaturedImageOnCustomPostMutationResolver(RemoveFeaturedImageOnCustomPostMutationResolver $removeFeaturedImageOnCustomPostMutationResolver): void
    {
        $this->removeFeaturedImageOnCustomPostMutationResolver = $removeFeaturedImageOnCustomPostMutationResolver;
    }
    final protected function getRemoveFeaturedImageOnCustomPostMutationResolver(): RemoveFeaturedImageOnCustomPostMutationResolver
    {
        return $this->removeFeaturedImageOnCustomPostMutationResolver ??= $this->instanceManager->getInstance(RemoveFeaturedImageOnCustomPostMutationResolver::class);
    }
    final public function setRootSetFeaturedImageOnCustomPostFilterInputObjectTypeResolver(RootSetFeaturedImageOnCustomPostFilterInputObjectTypeResolver $rootSetFeaturedImageOnCustomPostFilterInputObjectTypeResolver): void
    {
        $this->rootSetFeaturedImageOnCustomPostFilterInputObjectTypeResolver = $rootSetFeaturedImageOnCustomPostFilterInputObjectTypeResolver;
    }
    final protected function getRootSetFeaturedImageOnCustomPostFilterInputObjectTypeResolver(): RootSetFeaturedImageOnCustomPostFilterInputObjectTypeResolver
    {
        return $this->rootSetFeaturedImageOnCustomPostFilterInputObjectTypeResolver ??= $this->instanceManager->getInstance(RootSetFeaturedImageOnCustomPostFilterInputObjectTypeResolver::class);
    }
    final public function setRootRemoveFeaturedImageFromCustomPostFilterInputObjectTypeResolver(RootRemoveFeaturedImageFromCustomPostFilterInputObjectTypeResolver $rootRemoveFeaturedImageFromCustomPostFilterInputObjectTypeResolver): void
    {
        $this->rootRemoveFeaturedImageFromCustomPostFilterInputObjectTypeResolver = $rootRemoveFeaturedImageFromCustomPostFilterInputObjectTypeResolver;
    }
    final protected function getRootRemoveFeaturedImageFromCustomPostFilterInputObjectTypeResolver(): RootRemoveFeaturedImageFromCustomPostFilterInputObjectTypeResolver
    {
        return $this->rootRemoveFeaturedImageFromCustomPostFilterInputObjectTypeResolver ??= $this->instanceManager->getInstance(RootRemoveFeaturedImageFromCustomPostFilterInputObjectTypeResolver::class);
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        /** @var EngineComponentConfiguration */
        $componentConfiguration = ComponentManager::getComponent(EngineComponent::class)->getConfiguration();
        if ($componentConfiguration->disableRedundantRootTypeMutationFields()) {
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
            'setFeaturedImageOnCustomPost' => $this->getTranslationAPI()->__('Set the featured image on a custom post', 'custompostmedia-mutations'),
            'removeFeaturedImageFromCustomPost' => $this->getTranslationAPI()->__('Remove the featured image from a custom post', 'custompostmedia-mutations'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

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
        return match ($fieldName) {
            'setFeaturedImageOnCustomPost' => $this->getSetFeaturedImageOnCustomPostMutationResolver(),
            'removeFeaturedImageFromCustomPost' => $this->getRemoveFeaturedImageOnCustomPostMutationResolver(),
            default => parent::getFieldMutationResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'setFeaturedImageOnCustomPost',
            'removeFeaturedImageFromCustomPost'
                => $this->getCustomPostUnionTypeResolver(),
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
