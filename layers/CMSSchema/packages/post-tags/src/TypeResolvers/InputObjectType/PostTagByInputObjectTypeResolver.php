<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTags\TypeResolvers\InputObjectType;

use PoPCMSSchema\Tags\TypeResolvers\InputObjectType\AbstractTagByInputObjectTypeResolver;

class PostTagByInputObjectTypeResolver extends AbstractTagByInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'PostTagByInput';
    }
}
