<?php

declare(strict_types=1);

namespace PoPSchema\PostTags\TypeResolvers\InputObjectType;

use PoPSchema\Tags\TypeResolvers\InputObjectType\AbstractTagByInputObjectTypeResolver;

class PostTagByInputObjectTypeResolver extends AbstractTagByInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'PostTagByInput';
    }
}
