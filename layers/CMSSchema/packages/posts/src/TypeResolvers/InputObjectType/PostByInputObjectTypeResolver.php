<?php

declare(strict_types=1);

namespace PoPCMSSchema\Posts\TypeResolvers\InputObjectType;

use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\AbstractCustomPostByInputObjectTypeResolver;

class PostByInputObjectTypeResolver extends AbstractCustomPostByInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'PostByInput';
    }

    protected function getTypeDescriptionCustomPostEntity(): string
    {
        return $this->__('a post', 'posts');
    }
}
