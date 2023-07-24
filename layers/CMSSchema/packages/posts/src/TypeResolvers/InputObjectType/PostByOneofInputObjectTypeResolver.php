<?php

declare(strict_types=1);

namespace PoPCMSSchema\Posts\TypeResolvers\InputObjectType;

use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\AbstractCustomPostByOneofInputObjectTypeResolver;

class PostByOneofInputObjectTypeResolver extends AbstractCustomPostByOneofInputObjectTypeResolver
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
