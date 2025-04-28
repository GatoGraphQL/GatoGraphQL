<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostMetaMutations\FieldResolvers\ObjectType\AbstractCustomPostObjectTypeFieldResolver;
use PoPCMSSchema\CustomPostMetaMutations\Module as CustomPostMetaMutationsModule;
use PoPCMSSchema\CustomPostMetaMutations\ModuleConfiguration as CustomPostMetaMutationsModuleConfiguration;
use PoPCMSSchema\Pages\TypeResolvers\ObjectType\PageObjectTypeResolver;
use PoPCMSSchema\PageMetaMutations\TypeResolvers\ObjectType\PageAddMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\PageMetaMutations\TypeResolvers\ObjectType\PageDeleteMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\PageMetaMutations\TypeResolvers\ObjectType\PageSetMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\PageMetaMutations\TypeResolvers\ObjectType\PageUpdateMetaMutationPayloadObjectTypeResolver;
use PoP\ComponentModel\App;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class PageObjectTypeFieldResolver extends AbstractCustomPostObjectTypeFieldResolver
{
    private ?PageObjectTypeResolver $postObjectTypeResolver = null;
    private ?PageDeleteMetaMutationPayloadObjectTypeResolver $postDeleteMetaMutationPayloadObjectTypeResolver = null;
    private ?PageAddMetaMutationPayloadObjectTypeResolver $postCreateMutationPayloadObjectTypeResolver = null;
    private ?PageUpdateMetaMutationPayloadObjectTypeResolver $postUpdateMetaMutationPayloadObjectTypeResolver = null;
    private ?PageSetMetaMutationPayloadObjectTypeResolver $postSetMetaMutationPayloadObjectTypeResolver = null;

    final protected function getPageObjectTypeResolver(): PageObjectTypeResolver
    {
        if ($this->postObjectTypeResolver === null) {
            /** @var PageObjectTypeResolver */
            $postObjectTypeResolver = $this->instanceManager->getInstance(PageObjectTypeResolver::class);
            $this->postObjectTypeResolver = $postObjectTypeResolver;
        }
        return $this->postObjectTypeResolver;
    }
    final protected function getPageDeleteMetaMutationPayloadObjectTypeResolver(): PageDeleteMetaMutationPayloadObjectTypeResolver
    {
        if ($this->postDeleteMetaMutationPayloadObjectTypeResolver === null) {
            /** @var PageDeleteMetaMutationPayloadObjectTypeResolver */
            $postDeleteMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(PageDeleteMetaMutationPayloadObjectTypeResolver::class);
            $this->postDeleteMetaMutationPayloadObjectTypeResolver = $postDeleteMetaMutationPayloadObjectTypeResolver;
        }
        return $this->postDeleteMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getPageAddMetaMutationPayloadObjectTypeResolver(): PageAddMetaMutationPayloadObjectTypeResolver
    {
        if ($this->postCreateMutationPayloadObjectTypeResolver === null) {
            /** @var PageAddMetaMutationPayloadObjectTypeResolver */
            $postCreateMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(PageAddMetaMutationPayloadObjectTypeResolver::class);
            $this->postCreateMutationPayloadObjectTypeResolver = $postCreateMutationPayloadObjectTypeResolver;
        }
        return $this->postCreateMutationPayloadObjectTypeResolver;
    }
    final protected function getPageUpdateMetaMutationPayloadObjectTypeResolver(): PageUpdateMetaMutationPayloadObjectTypeResolver
    {
        if ($this->postUpdateMetaMutationPayloadObjectTypeResolver === null) {
            /** @var PageUpdateMetaMutationPayloadObjectTypeResolver */
            $postUpdateMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(PageUpdateMetaMutationPayloadObjectTypeResolver::class);
            $this->postUpdateMetaMutationPayloadObjectTypeResolver = $postUpdateMetaMutationPayloadObjectTypeResolver;
        }
        return $this->postUpdateMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getPageSetMetaMutationPayloadObjectTypeResolver(): PageSetMetaMutationPayloadObjectTypeResolver
    {
        if ($this->postSetMetaMutationPayloadObjectTypeResolver === null) {
            /** @var PageSetMetaMutationPayloadObjectTypeResolver */
            $postSetMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(PageSetMetaMutationPayloadObjectTypeResolver::class);
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
            PageObjectTypeResolver::class,
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
                    => $this->getPageObjectTypeResolver(),
                default
                    => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
            };
        }
        return match ($fieldName) {
            'addMeta' => $this->getPageAddMetaMutationPayloadObjectTypeResolver(),
            'deleteMeta' => $this->getPageDeleteMetaMutationPayloadObjectTypeResolver(),
            'setMeta' => $this->getPageSetMetaMutationPayloadObjectTypeResolver(),
            'updateMeta' => $this->getPageUpdateMetaMutationPayloadObjectTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
