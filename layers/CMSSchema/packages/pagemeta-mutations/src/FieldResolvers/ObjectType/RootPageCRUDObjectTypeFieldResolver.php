<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\Pages\TypeResolvers\ObjectType\PageObjectTypeResolver;
use PoPCMSSchema\CustomPostMetaMutations\FieldResolvers\ObjectType\AbstractRootCustomPostCRUDObjectTypeFieldResolver;
use PoPCMSSchema\CustomPostMetaMutations\Module;
use PoPCMSSchema\CustomPostMetaMutations\ModuleConfiguration;
use PoPCMSSchema\PageMetaMutations\TypeResolvers\ObjectType\RootAddPageMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\PageMetaMutations\TypeResolvers\ObjectType\RootDeletePageMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\PageMetaMutations\TypeResolvers\ObjectType\RootSetPageMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\PageMetaMutations\TypeResolvers\ObjectType\RootUpdatePageMetaMutationPayloadObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Root\App;

/**
 * Made abstract to not initialize class (it's disabled)
 */
abstract class RootPageCRUDObjectTypeFieldResolver extends AbstractRootCustomPostCRUDObjectTypeFieldResolver
{
    private ?PageObjectTypeResolver $pageObjectTypeResolver = null;
    private ?RootDeletePageMetaMutationPayloadObjectTypeResolver $rootDeletePageMetaMutationPayloadObjectTypeResolver = null;
    private ?RootSetPageMetaMutationPayloadObjectTypeResolver $rootSetPageMetaMutationPayloadObjectTypeResolver = null;
    private ?RootUpdatePageMetaMutationPayloadObjectTypeResolver $rootUpdatePageMetaMutationPayloadObjectTypeResolver = null;
    private ?RootAddPageMetaMutationPayloadObjectTypeResolver $rootAddPageMetaMutationPayloadObjectTypeResolver = null;

    final protected function getPageObjectTypeResolver(): PageObjectTypeResolver
    {
        if ($this->pageObjectTypeResolver === null) {
            /** @var PageObjectTypeResolver */
            $pageObjectTypeResolver = $this->instanceManager->getInstance(PageObjectTypeResolver::class);
            $this->pageObjectTypeResolver = $pageObjectTypeResolver;
        }
        return $this->pageObjectTypeResolver;
    }
    final protected function getRootDeletePageMetaMutationPayloadObjectTypeResolver(): RootDeletePageMetaMutationPayloadObjectTypeResolver
    {
        if ($this->rootDeletePageMetaMutationPayloadObjectTypeResolver === null) {
            /** @var RootDeletePageMetaMutationPayloadObjectTypeResolver */
            $rootDeletePageMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootDeletePageMetaMutationPayloadObjectTypeResolver::class);
            $this->rootDeletePageMetaMutationPayloadObjectTypeResolver = $rootDeletePageMetaMutationPayloadObjectTypeResolver;
        }
        return $this->rootDeletePageMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getRootSetPageMetaMutationPayloadObjectTypeResolver(): RootSetPageMetaMutationPayloadObjectTypeResolver
    {
        if ($this->rootSetPageMetaMutationPayloadObjectTypeResolver === null) {
            /** @var RootSetPageMetaMutationPayloadObjectTypeResolver */
            $rootSetPageMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootSetPageMetaMutationPayloadObjectTypeResolver::class);
            $this->rootSetPageMetaMutationPayloadObjectTypeResolver = $rootSetPageMetaMutationPayloadObjectTypeResolver;
        }
        return $this->rootSetPageMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getRootUpdatePageMetaMutationPayloadObjectTypeResolver(): RootUpdatePageMetaMutationPayloadObjectTypeResolver
    {
        if ($this->rootUpdatePageMetaMutationPayloadObjectTypeResolver === null) {
            /** @var RootUpdatePageMetaMutationPayloadObjectTypeResolver */
            $rootUpdatePageMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootUpdatePageMetaMutationPayloadObjectTypeResolver::class);
            $this->rootUpdatePageMetaMutationPayloadObjectTypeResolver = $rootUpdatePageMetaMutationPayloadObjectTypeResolver;
        }
        return $this->rootUpdatePageMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getRootAddPageMetaMutationPayloadObjectTypeResolver(): RootAddPageMetaMutationPayloadObjectTypeResolver
    {
        if ($this->rootAddPageMetaMutationPayloadObjectTypeResolver === null) {
            /** @var RootAddPageMetaMutationPayloadObjectTypeResolver */
            $rootAddPageMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootAddPageMetaMutationPayloadObjectTypeResolver::class);
            $this->rootAddPageMetaMutationPayloadObjectTypeResolver = $rootAddPageMetaMutationPayloadObjectTypeResolver;
        }
        return $this->rootAddPageMetaMutationPayloadObjectTypeResolver;
    }

    /**
     * Disable because we don't need `addPageMeta` and
     * `addCustomPostMeta`, it's too confusing
     */
    public function isServiceEnabled(): bool
    {
        return false;
    }

    protected function getCustomPostEntityName(): string
    {
        return 'Page';
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        $customPageEntityName = $this->getCustomPostEntityName();
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableCustomPostMetaMutations = $moduleConfiguration->usePayloadableCustomPostMetaMutations();
        if ($usePayloadableCustomPostMetaMutations) {
            return match ($fieldName) {
                'add' . $customPageEntityName . 'Meta',
                'add' . $customPageEntityName . 'Metas',
                'add' . $customPageEntityName . 'MetaMutationPayloadObjects'
                    => $this->getRootAddPageMetaMutationPayloadObjectTypeResolver(),
                'update' . $customPageEntityName . 'Meta',
                'update' . $customPageEntityName . 'Metas',
                'update' . $customPageEntityName . 'MetaMutationPayloadObjects'
                    => $this->getRootUpdatePageMetaMutationPayloadObjectTypeResolver(),
                'delete' . $customPageEntityName . 'Meta',
                'delete' . $customPageEntityName . 'Metas',
                'delete' . $customPageEntityName . 'MetaMutationPayloadObjects'
                    => $this->getRootDeletePageMetaMutationPayloadObjectTypeResolver(),
                'set' . $customPageEntityName . 'Meta',
                'set' . $customPageEntityName . 'Metas',
                'set' . $customPageEntityName . 'MetaMutationPayloadObjects'
                    => $this->getRootSetPageMetaMutationPayloadObjectTypeResolver(),
                default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
            };
        }
        return match ($fieldName) {
            'add' . $customPageEntityName . 'Meta',
            'add' . $customPageEntityName . 'Metas',
            'update' . $customPageEntityName . 'Meta',
            'update' . $customPageEntityName . 'Metas',
            'delete' . $customPageEntityName . 'Meta',
            'delete' . $customPageEntityName . 'Metas',
            'set' . $customPageEntityName . 'Meta',
            'set' . $customPageEntityName . 'Metas'
                => $this->getPageObjectTypeResolver(),
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
