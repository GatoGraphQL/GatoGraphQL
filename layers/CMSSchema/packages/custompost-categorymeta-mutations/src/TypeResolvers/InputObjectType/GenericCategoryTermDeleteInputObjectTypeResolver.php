<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\CategoryMutations\TypeResolvers\InputObjectType\CategoryTermDeleteInputObjectTypeResolverTrait;

class GenericCategoryTermDeleteInputObjectTypeResolver extends AbstractCreateOrUpdateGenericCategoryTermInputObjectTypeResolver implements DeleteGenericCategoryTermInputObjectTypeResolverInterface
{
    use CategoryTermDeleteInputObjectTypeResolverTrait;

    public function getTypeName(): string
    {
        return 'GenericCategoryDeleteInput';
    }

    protected function isTaxonomyInputFieldMandatory(): bool
    {
        return false;
    }
}
