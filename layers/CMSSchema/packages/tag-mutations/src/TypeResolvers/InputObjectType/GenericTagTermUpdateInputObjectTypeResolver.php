<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMutations\TypeResolvers\InputObjectType;

class GenericTagTermUpdateInputObjectTypeResolver extends TagTermUpdateInputObjectTypeResolver implements UpdateGenericTagTermInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'GenericTagUpdateInput';
    }
}
