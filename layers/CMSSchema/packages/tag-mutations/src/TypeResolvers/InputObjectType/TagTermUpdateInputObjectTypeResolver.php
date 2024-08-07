<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMutations\TypeResolvers\InputObjectType;

class TagTermUpdateInputObjectTypeResolver extends AbstractCreateOrUpdateTagTermInputObjectTypeResolver implements UpdateTagTermInputObjectTypeResolverInterface
{
    use TagTermUpdateInputObjectTypeResolverTrait;

    public function getTypeName(): string
    {
        return 'TagUpdateInput';
    }
}
