<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTags\TypeResolvers\InputObjectType;

use PoPCMSSchema\Tags\TypeResolvers\InputObjectType\AbstractTagByOneofInputObjectTypeResolver;

class PostTagByOneofInputObjectTypeResolver extends AbstractTagByOneofInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'PostTagByInput';
    }
}
