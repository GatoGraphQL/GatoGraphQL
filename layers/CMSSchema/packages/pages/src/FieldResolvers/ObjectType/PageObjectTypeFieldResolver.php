<?php

declare(strict_types=1);

namespace PoPCMSSchema\Pages\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPosts\FieldResolvers\ObjectType\AbstractWithParentCustomPostObjectTypeFieldResolver;
use PoPCMSSchema\Pages\TypeResolvers\InputObjectType\PagePaginationInputObjectTypeResolver;
use PoPCMSSchema\Pages\TypeResolvers\ObjectType\PageObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class PageObjectTypeFieldResolver extends AbstractWithParentCustomPostObjectTypeFieldResolver
{
    private ?PageObjectTypeResolver $pageObjectTypeResolver = null;
    private ?PagePaginationInputObjectTypeResolver $pagePaginationInputObjectTypeResolver = null;

    final protected function getPageObjectTypeResolver(): PageObjectTypeResolver
    {
        if ($this->pageObjectTypeResolver === null) {
            /** @var PageObjectTypeResolver */
            $pageObjectTypeResolver = $this->instanceManager->getInstance(PageObjectTypeResolver::class);
            $this->pageObjectTypeResolver = $pageObjectTypeResolver;
        }
        return $this->pageObjectTypeResolver;
    }
    final protected function getPagePaginationInputObjectTypeResolver(): PagePaginationInputObjectTypeResolver
    {
        if ($this->pagePaginationInputObjectTypeResolver === null) {
            /** @var PagePaginationInputObjectTypeResolver */
            $pagePaginationInputObjectTypeResolver = $this->instanceManager->getInstance(PagePaginationInputObjectTypeResolver::class);
            $this->pagePaginationInputObjectTypeResolver = $pagePaginationInputObjectTypeResolver;
        }
        return $this->pagePaginationInputObjectTypeResolver;
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

    protected function getCustomPostFieldTypeResolver(): ConcreteTypeResolverInterface
    {
        return $this->getPageObjectTypeResolver();
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'parent' => $this->__('Parent page', 'pages'),
            'ancestors' => $this->__('List of all ancestor pages (parent, grandparent, etc.)', 'pages'),
            'children' => $this->__('Child pages', 'pages'),
            'childCount' => $this->__('Number of child pages', 'pages'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        $fieldArgNameTypeResolvers = parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName);
        if ($fieldName === 'children') {
            $fieldArgNameTypeResolvers['pagination'] = $this->getPagePaginationInputObjectTypeResolver();
        }
        return $fieldArgNameTypeResolvers;
    }
}
