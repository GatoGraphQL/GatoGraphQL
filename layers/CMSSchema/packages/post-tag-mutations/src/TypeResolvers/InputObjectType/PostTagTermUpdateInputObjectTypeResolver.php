<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\TagMutations\TypeResolvers\InputObjectType\TagTermUpdateInputObjectTypeResolverTrait;

class PostTagTermUpdateInputObjectTypeResolver extends AbstractCreateOrUpdatePostTagTermInputObjectTypeResolver implements UpdatePostTagTermInputObjectTypeResolverInterface
{
    use TagTermUpdateInputObjectTypeResolverTrait;

    public function getTypeName(): string
    {
        return 'PostTagUpdateInput';
    }
}
