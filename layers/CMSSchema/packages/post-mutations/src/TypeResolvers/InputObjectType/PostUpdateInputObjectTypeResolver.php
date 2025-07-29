<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType\AbstractCustomPostContentAsOneofInputObjectTypeResolver;
use PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType\AbstractCustomPostUpdateInputObjectTypeResolver;

class PostUpdateInputObjectTypeResolver extends AbstractCustomPostUpdateInputObjectTypeResolver implements UpdatePostInputObjectTypeResolverInterface
{
    private ?PostContentAsOneofInputObjectTypeResolver $postContentAsOneofInputObjectTypeResolver = null;

    final protected function getPostContentAsOneofInputObjectTypeResolver(): PostContentAsOneofInputObjectTypeResolver
    {
        if ($this->postContentAsOneofInputObjectTypeResolver === null) {
            /** @var PostContentAsOneofInputObjectTypeResolver */
            $postContentAsOneofInputObjectTypeResolver = $this->instanceManager->getInstance(PostContentAsOneofInputObjectTypeResolver::class);
            $this->postContentAsOneofInputObjectTypeResolver = $postContentAsOneofInputObjectTypeResolver;
        }
        return $this->postContentAsOneofInputObjectTypeResolver;
    }

    public function getTypeName(): string
    {
        return 'PostUpdateInput';
    }

    protected function addCustomPostParentInputField(): bool
    {
        return false;
    }

    protected function getContentAsOneofInputObjectTypeResolver(): AbstractCustomPostContentAsOneofInputObjectTypeResolver
    {
        return $this->getPostContentAsOneofInputObjectTypeResolver();
    }
}
