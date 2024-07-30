<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\TypeResolvers\InputObjectType;

class GenericCategoryTermUpdateInputObjectTypeResolver extends CategoryTermUpdateInputObjectTypeResolver implements UpdateGenericCategoryTermInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'GenericCategoryUpdateInput';
    }
}
