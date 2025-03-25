<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\CategoryMutations\TypeResolvers\InputObjectType\CategoryTermMetaCreateInputObjectTypeResolverTrait;

class GenericCategoryTermMetaCreateInputObjectTypeResolver extends AbstractCreateOrUpdateGenericCategoryTermMetaInputObjectTypeResolver implements CreateGenericCategoryTermMetaInputObjectTypeResolverInterface
{
    use CategoryTermMetaCreateInputObjectTypeResolverTrait;

    public function getTypeName(): string
    {
        return 'GenericCategoryCreateInput';
    }

    protected function isTaxonomyInputFieldMandatory(): bool
    {
        return false;
    }
}
