<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostMetaMutations\FieldResolvers\ObjectType\AbstractCustomPostObjectTypeFieldResolver;
use PoPCMSSchema\CustomPostMetaMutations\Module as CustomPostMetaMutationsModule;
use PoPCMSSchema\CustomPostMetaMutations\ModuleConfiguration as CustomPostMetaMutationsModuleConfiguration;
use PoPCMSSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoPCMSSchema\PostMetaMutations\TypeResolvers\ObjectType\PostAddMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\PostMetaMutations\TypeResolvers\ObjectType\PostDeleteMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\PostMetaMutations\TypeResolvers\ObjectType\PostSetMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\PostMetaMutations\TypeResolvers\ObjectType\PostUpdateMetaMutationPayloadObjectTypeResolver;
use PoP\ComponentModel\App;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class PostObjectTypeFieldResolver extends AbstractCustomPostObjectTypeFieldResolver
{
    private ?PostObjectTypeResolver $postObjectTypeResolver = null;
    private ?PostDeleteMetaMutationPayloadObjectTypeResolver $postDeleteMetaMutationPayloadObjectTypeResolver = null;
    private ?PostAddMetaMutationPayloadObjectTypeResolver $postCreateMutationPayloadObjectTypeResolver = null;
    private ?PostUpdateMetaMutationPayloadObjectTypeResolver $postUpdateMetaMutationPayloadObjectTypeResolver = null;
    private ?PostSetMetaMutationPayloadObjectTypeResolver $postSetMetaMutationPayloadObjectTypeResolver = null;

    final protected function getPostObjectTypeResolver(): PostObjectTypeResolver
    {
        if ($this->postObjectTypeResolver === null) {
            /** @var PostObjectTypeResolver */
            $postObjectTypeResolver = $this->instanceManager->getInstance(PostObjectTypeResolver::class);
            $this->postObjectTypeResolver = $postObjectTypeResolver;
        }
        return $this->postObjectTypeResolver;
    }
    final protected function getPostDeleteMetaMutationPayloadObjectTypeResolver(): PostDeleteMetaMutationPayloadObjectTypeResolver
    {
        if ($this->postDeleteMetaMutationPayloadObjectTypeResolver === null) {
            /** @var PostDeleteMetaMutationPayloadObjectTypeResolver */
            $postDeleteMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(PostDeleteMetaMutationPayloadObjectTypeResolver::class);
            $this->postDeleteMetaMutationPayloadObjectTypeResolver = $postDeleteMetaMutationPayloadObjectTypeResolver;
        }
        return $this->postDeleteMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getPostAddMetaMutationPayloadObjectTypeResolver(): PostAddMetaMutationPayloadObjectTypeResolver
    {
        if ($this->postCreateMutationPayloadObjectTypeResolver === null) {
            /** @var PostAddMetaMutationPayloadObjectTypeResolver */
            $postCreateMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(PostAddMetaMutationPayloadObjectTypeResolver::class);
            $this->postCreateMutationPayloadObjectTypeResolver = $postCreateMutationPayloadObjectTypeResolver;
        }
        return $this->postCreateMutationPayloadObjectTypeResolver;
    }
    final protected function getPostUpdateMetaMutationPayloadObjectTypeResolver(): PostUpdateMetaMutationPayloadObjectTypeResolver
    {
        if ($this->postUpdateMetaMutationPayloadObjectTypeResolver === null) {
            /** @var PostUpdateMetaMutationPayloadObjectTypeResolver */
            $postUpdateMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(PostUpdateMetaMutationPayloadObjectTypeResolver::class);
            $this->postUpdateMetaMutationPayloadObjectTypeResolver = $postUpdateMetaMutationPayloadObjectTypeResolver;
        }
        return $this->postUpdateMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getPostSetMetaMutationPayloadObjectTypeResolver(): PostSetMetaMutationPayloadObjectTypeResolver
    {
        if ($this->postSetMetaMutationPayloadObjectTypeResolver === null) {
            /** @var PostSetMetaMutationPayloadObjectTypeResolver */
            $postSetMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(PostSetMetaMutationPayloadObjectTypeResolver::class);
            $this->postSetMetaMutationPayloadObjectTypeResolver = $postSetMetaMutationPayloadObjectTypeResolver;
        }
        return $this->postSetMetaMutationPayloadObjectTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            PostObjectTypeResolver::class,
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        /** @var CustomPostMetaMutationsModuleConfiguration */
        $moduleConfiguration = App::getModule(CustomPostMetaMutationsModule::class)->getConfiguration();
        $usePayloadableCustomPostMetaMutations = $moduleConfiguration->usePayloadableCustomPostMetaMutations();
        if (!$usePayloadableCustomPostMetaMutations) {
            return match ($fieldName) {
                'addMeta',
                'deleteMeta',
                'setMeta',
                'updateMeta'
                    => $this->getPostObjectTypeResolver(),
                default
                    => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
            };
        }
        return match ($fieldName) {
            'addMeta' => $this->getPostAddMetaMutationPayloadObjectTypeResolver(),
            'deleteMeta' => $this->getPostDeleteMetaMutationPayloadObjectTypeResolver(),
            'setMeta' => $this->getPostSetMetaMutationPayloadObjectTypeResolver(),
            'updateMeta' => $this->getPostUpdateMetaMutationPayloadObjectTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
