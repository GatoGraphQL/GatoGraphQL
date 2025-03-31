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
    private ?PostObjectTypeResolver $postCustomPostObjectTypeResolver = null;
    private ?PostDeleteMetaMutationPayloadObjectTypeResolver $postCustomPostDeleteMetaMutationPayloadObjectTypeResolver = null;
    private ?PostAddMetaMutationPayloadObjectTypeResolver $postCustomPostCreateMutationPayloadObjectTypeResolver = null;
    private ?PostUpdateMetaMutationPayloadObjectTypeResolver $postCustomPostUpdateMetaMutationPayloadObjectTypeResolver = null;
    private ?PostSetMetaMutationPayloadObjectTypeResolver $postCustomPostSetMetaMutationPayloadObjectTypeResolver = null;

    final protected function getPostObjectTypeResolver(): PostObjectTypeResolver
    {
        if ($this->postCustomPostObjectTypeResolver === null) {
            /** @var PostObjectTypeResolver */
            $postCustomPostObjectTypeResolver = $this->instanceManager->getInstance(PostObjectTypeResolver::class);
            $this->postCustomPostObjectTypeResolver = $postCustomPostObjectTypeResolver;
        }
        return $this->postCustomPostObjectTypeResolver;
    }
    final protected function getPostDeleteMetaMutationPayloadObjectTypeResolver(): PostDeleteMetaMutationPayloadObjectTypeResolver
    {
        if ($this->postCustomPostDeleteMetaMutationPayloadObjectTypeResolver === null) {
            /** @var PostDeleteMetaMutationPayloadObjectTypeResolver */
            $postCustomPostDeleteMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(PostDeleteMetaMutationPayloadObjectTypeResolver::class);
            $this->postCustomPostDeleteMetaMutationPayloadObjectTypeResolver = $postCustomPostDeleteMetaMutationPayloadObjectTypeResolver;
        }
        return $this->postCustomPostDeleteMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getPostAddMetaMutationPayloadObjectTypeResolver(): PostAddMetaMutationPayloadObjectTypeResolver
    {
        if ($this->postCustomPostCreateMutationPayloadObjectTypeResolver === null) {
            /** @var PostAddMetaMutationPayloadObjectTypeResolver */
            $postCustomPostCreateMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(PostAddMetaMutationPayloadObjectTypeResolver::class);
            $this->postCustomPostCreateMutationPayloadObjectTypeResolver = $postCustomPostCreateMutationPayloadObjectTypeResolver;
        }
        return $this->postCustomPostCreateMutationPayloadObjectTypeResolver;
    }
    final protected function getPostUpdateMetaMutationPayloadObjectTypeResolver(): PostUpdateMetaMutationPayloadObjectTypeResolver
    {
        if ($this->postCustomPostUpdateMetaMutationPayloadObjectTypeResolver === null) {
            /** @var PostUpdateMetaMutationPayloadObjectTypeResolver */
            $postCustomPostUpdateMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(PostUpdateMetaMutationPayloadObjectTypeResolver::class);
            $this->postCustomPostUpdateMetaMutationPayloadObjectTypeResolver = $postCustomPostUpdateMetaMutationPayloadObjectTypeResolver;
        }
        return $this->postCustomPostUpdateMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getPostSetMetaMutationPayloadObjectTypeResolver(): PostSetMetaMutationPayloadObjectTypeResolver
    {
        if ($this->postCustomPostSetMetaMutationPayloadObjectTypeResolver === null) {
            /** @var PostSetMetaMutationPayloadObjectTypeResolver */
            $postCustomPostSetMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(PostSetMetaMutationPayloadObjectTypeResolver::class);
            $this->postCustomPostSetMetaMutationPayloadObjectTypeResolver = $postCustomPostSetMetaMutationPayloadObjectTypeResolver;
        }
        return $this->postCustomPostSetMetaMutationPayloadObjectTypeResolver;
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
