<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\CategoryMutations\TypeResolvers\InputObjectType\CategoryTermMetaDeleteInputObjectTypeResolverTrait;

class GenericCategoryTermMetaDeleteInputObjectTypeResolver extends AbstractCreateOrUpdateGenericCategoryTermMetaInputObjectTypeResolver implements DeleteGenericCategoryTermMetaInputObjectTypeResolverInterface
{
    use CategoryTermMetaDeleteInputObjectTypeResolverTrait;

    public function getTypeName(): string
    {
        return 'GenericCategoryDeleteInput';
    }

    protected function isTaxonomyInputFieldMandatory(): bool
    {
        return false;
    }
}
