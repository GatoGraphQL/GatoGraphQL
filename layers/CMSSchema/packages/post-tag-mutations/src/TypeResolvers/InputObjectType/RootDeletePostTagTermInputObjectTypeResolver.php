<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\TagMutations\TypeResolvers\InputObjectType\RootDeleteTagTermInputObjectTypeResolverTrait;

class RootDeletePostTagTermInputObjectTypeResolver extends AbstractDeletePostTagTermInputObjectTypeResolver implements DeletePostTagTermInputObjectTypeResolverInterface
{
    use RootDeleteTagTermInputObjectTypeResolverTrait;

    public function getTypeName(): string
    {
        return 'RootDeletePostTagInput';
    }
}
