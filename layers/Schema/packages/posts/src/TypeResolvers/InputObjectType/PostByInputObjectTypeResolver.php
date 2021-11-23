<?php

declare(strict_types=1);

namespace PoPSchema\Posts\TypeResolvers\InputObjectType;

use PoPSchema\CustomPosts\TypeResolvers\InputObjectType\AbstractCustomPostByInputObjectTypeResolver;

class PostByInputObjectTypeResolver extends AbstractCustomPostByInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'PostByInput';
    }

    protected function getTypeDescriptionCustomPostEntity(): string
    {
        return $this->getTranslationAPI()->__('a post', 'posts');
    }
}
