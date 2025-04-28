<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMetaMutations\SchemaHooks;

use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\CustomPostObjectTypeResolverInterface;
use PoPCMSSchema\Pages\TypeResolvers\ObjectType\PageObjectTypeResolver;
use PoPCMSSchema\CustomPostMetaMutations\SchemaHooks\AbstractCustomPostMutationResolverHookSet;

class PageMutationResolverHookSet extends AbstractCustomPostMutationResolverHookSet
{
    use PageMutationResolverHookSetTrait;

    private ?PageObjectTypeResolver $postObjectTypeResolver = null;

    final protected function getPageObjectTypeResolver(): PageObjectTypeResolver
    {
        if ($this->postObjectTypeResolver === null) {
            /** @var PageObjectTypeResolver */
            $postObjectTypeResolver = $this->instanceManager->getInstance(PageObjectTypeResolver::class);
            $this->postObjectTypeResolver = $postObjectTypeResolver;
        }
        return $this->postObjectTypeResolver;
    }

    protected function getCustomPostTypeResolver(): CustomPostObjectTypeResolverInterface
    {
        return $this->getPageObjectTypeResolver();
    }
}
