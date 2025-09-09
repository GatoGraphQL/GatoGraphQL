<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMetaMutations\SchemaHooks;

use PoPCMSSchema\CustomPostMetaMutations\SchemaHooks\AbstractCustomPostMutationResolverHookSet;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\CustomPostObjectTypeResolverInterface;
use PoPCMSSchema\PageMutations\SchemaHooks\PageMutationResolverHookSetTrait;
use PoPCMSSchema\Pages\TypeResolvers\ObjectType\PageObjectTypeResolver;

class PageMutationResolverHookSet extends AbstractCustomPostMutationResolverHookSet
{
    use PageMutationResolverHookSetTrait;

    private ?PageObjectTypeResolver $pageObjectTypeResolver = null;

    final protected function getPageObjectTypeResolver(): PageObjectTypeResolver
    {
        if ($this->pageObjectTypeResolver === null) {
            /** @var PageObjectTypeResolver */
            $pageObjectTypeResolver = $this->instanceManager->getInstance(PageObjectTypeResolver::class);
            $this->pageObjectTypeResolver = $pageObjectTypeResolver;
        }
        return $this->pageObjectTypeResolver;
    }

    protected function getCustomPostTypeResolver(): CustomPostObjectTypeResolverInterface
    {
        return $this->getPageObjectTypeResolver();
    }
}
