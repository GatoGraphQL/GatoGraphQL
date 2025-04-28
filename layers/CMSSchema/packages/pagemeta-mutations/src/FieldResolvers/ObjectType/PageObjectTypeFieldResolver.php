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
    private ?PageObjectTypeResolver $pageObjectTypeResolver = null;
    private ?PageDeleteMetaMutationPayloadObjectTypeResolver $pageDeleteMetaMutationPayloadObjectTypeResolver = null;
    private ?PageAddMetaMutationPayloadObjectTypeResolver $pageCreateMutationPayloadObjectTypeResolver = null;
    private ?PageUpdateMetaMutationPayloadObjectTypeResolver $pageUpdateMetaMutationPayloadObjectTypeResolver = null;
    private ?PageSetMetaMutationPayloadObjectTypeResolver $pageSetMetaMutationPayloadObjectTypeResolver = null;

    final protected function getPageObjectTypeResolver(): PageObjectTypeResolver
    {
        if ($this->pageObjectTypeResolver === null) {
            /** @var PageObjectTypeResolver */
            $pageObjectTypeResolver = $this->instanceManager->getInstance(PageObjectTypeResolver::class);
            $this->pageObjectTypeResolver = $pageObjectTypeResolver;
        }
        return $this->pageObjectTypeResolver;
    }
    final protected function getPageDeleteMetaMutationPayloadObjectTypeResolver(): PageDeleteMetaMutationPayloadObjectTypeResolver
    {
        if ($this->pageDeleteMetaMutationPayloadObjectTypeResolver === null) {
            /** @var PageDeleteMetaMutationPayloadObjectTypeResolver */
            $pageDeleteMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(PageDeleteMetaMutationPayloadObjectTypeResolver::class);
            $this->pageDeleteMetaMutationPayloadObjectTypeResolver = $pageDeleteMetaMutationPayloadObjectTypeResolver;
        }
        return $this->pageDeleteMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getPageAddMetaMutationPayloadObjectTypeResolver(): PageAddMetaMutationPayloadObjectTypeResolver
    {
        if ($this->pageCreateMutationPayloadObjectTypeResolver === null) {
            /** @var PageAddMetaMutationPayloadObjectTypeResolver */
            $pageCreateMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(PageAddMetaMutationPayloadObjectTypeResolver::class);
            $this->pageCreateMutationPayloadObjectTypeResolver = $pageCreateMutationPayloadObjectTypeResolver;
        }
        return $this->pageCreateMutationPayloadObjectTypeResolver;
    }
    final protected function getPageUpdateMetaMutationPayloadObjectTypeResolver(): PageUpdateMetaMutationPayloadObjectTypeResolver
    {
        if ($this->pageUpdateMetaMutationPayloadObjectTypeResolver === null) {
            /** @var PageUpdateMetaMutationPayloadObjectTypeResolver */
            $pageUpdateMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(PageUpdateMetaMutationPayloadObjectTypeResolver::class);
            $this->pageUpdateMetaMutationPayloadObjectTypeResolver = $pageUpdateMetaMutationPayloadObjectTypeResolver;
        }
        return $this->pageUpdateMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getPageSetMetaMutationPayloadObjectTypeResolver(): PageSetMetaMutationPayloadObjectTypeResolver
    {
        if ($this->pageSetMetaMutationPayloadObjectTypeResolver === null) {
            /** @var PageSetMetaMutationPayloadObjectTypeResolver */
            $pageSetMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(PageSetMetaMutationPayloadObjectTypeResolver::class);
            $this->pageSetMetaMutationPayloadObjectTypeResolver = $pageSetMetaMutationPayloadObjectTypeResolver;
        }
        return $this->pageSetMetaMutationPayloadObjectTypeResolver;
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
