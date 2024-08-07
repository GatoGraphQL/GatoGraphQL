<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\TagMutations\TypeResolvers\InputObjectType\RootCreateTagTermInputObjectTypeResolverTrait;

class RootCreatePostTagTermInputObjectTypeResolver extends AbstractCreateOrUpdatePostTagTermInputObjectTypeResolver implements CreatePostTagTermInputObjectTypeResolverInterface
{
    use RootCreateTagTermInputObjectTypeResolverTrait;

    public function getTypeName(): string
    {
        return 'RootCreatePostTagInput';
    }
}
