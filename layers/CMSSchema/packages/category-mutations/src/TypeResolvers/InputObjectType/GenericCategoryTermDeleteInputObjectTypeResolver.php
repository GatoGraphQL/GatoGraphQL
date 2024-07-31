<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\TypeResolvers\InputObjectType;

class GenericCategoryTermDeleteInputObjectTypeResolver extends CategoryTermDeleteInputObjectTypeResolver implements DeleteGenericCategoryTermInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'GenericCategoryDeleteInput';
    }
}
