<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\TagMutations\TypeResolvers\InputObjectType\TagTermUpdateInputObjectTypeResolverTrait;

class GenericTagTermUpdateInputObjectTypeResolver extends AbstractCreateOrUpdateGenericTagTermInputObjectTypeResolver implements UpdateGenericTagTermInputObjectTypeResolverInterface
{
    use TagTermUpdateInputObjectTypeResolverTrait;

    public function getTypeName(): string
    {
        return 'GenericTagUpdateInput';
    }
}
