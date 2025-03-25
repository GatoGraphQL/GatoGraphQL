<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\CategoryMutations\TypeResolvers\InputObjectType\CategoryTermMetaUpdateInputObjectTypeResolverTrait;

class GenericCategoryTermMetaUpdateInputObjectTypeResolver extends AbstractCreateOrUpdateGenericCategoryTermInputObjectTypeResolver implements UpdateGenericCategoryTermInputObjectTypeResolverInterface
{
    use CategoryTermMetaUpdateInputObjectTypeResolverTrait;

    public function getTypeName(): string
    {
        return 'GenericCategoryUpdateInput';
    }

    protected function isTaxonomyInputFieldMandatory(): bool
    {
        return false;
    }
}
