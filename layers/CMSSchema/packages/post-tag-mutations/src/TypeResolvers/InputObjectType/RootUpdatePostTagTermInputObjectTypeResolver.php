<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\TagMutations\TypeResolvers\InputObjectType\RootUpdateTagTermInputObjectTypeResolverTrait;

class RootUpdatePostTagTermInputObjectTypeResolver extends AbstractCreateOrUpdatePostTagTermInputObjectTypeResolver implements UpdatePostTagTermInputObjectTypeResolverInterface
{
    use RootUpdateTagTermInputObjectTypeResolverTrait;

    public function getTypeName(): string
    {
        return 'RootUpdatePostTagInput';
    }
}
