<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType\CustomPostContentAsOneofInputObjectTypeResolver;
use PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType\RootCreateCustomPostFilterInputObjectTypeResolver;

class RootCreatePostFilterInputObjectTypeResolver extends RootCreateCustomPostFilterInputObjectTypeResolver implements CreatePostFilterInputObjectTypeResolverInterface
{
    private ?PostContentAsOneofInputObjectTypeResolver $postContentAsOneofInputObjectTypeResolver = null;

    final public function setPostContentAsOneofInputObjectTypeResolver(PostContentAsOneofInputObjectTypeResolver $postContentAsOneofInputObjectTypeResolver): void
    {
        $this->postContentAsOneofInputObjectTypeResolver = $postContentAsOneofInputObjectTypeResolver;
    }
    final protected function getPostContentAsOneofInputObjectTypeResolver(): PostContentAsOneofInputObjectTypeResolver
    {
        /** @var PostContentAsOneofInputObjectTypeResolver */
        return $this->postContentAsOneofInputObjectTypeResolver ??= $this->instanceManager->getInstance(PostContentAsOneofInputObjectTypeResolver::class);
    }
    
    public function getTypeName(): string
    {
        return 'RootCreatePostFilterInput';
    }

    protected function getContentAsOneofInputObjectTypeResolver(): CustomPostContentAsOneofInputObjectTypeResolver
    {
        return $this->getPostContentAsOneofInputObjectTypeResolver();
    }
}
