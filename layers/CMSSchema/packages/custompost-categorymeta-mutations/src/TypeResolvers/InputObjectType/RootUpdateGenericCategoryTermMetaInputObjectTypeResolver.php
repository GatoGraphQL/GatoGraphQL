<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\CategoryMutations\TypeResolvers\InputObjectType\RootUpdateCategoryTermMetaInputObjectTypeResolverTrait;

class RootUpdateGenericCategoryTermMetaInputObjectTypeResolver extends AbstractCreateOrUpdateGenericCategoryTermMetaInputObjectTypeResolver implements UpdateGenericCategoryTermMetaInputObjectTypeResolverInterface
{
    use RootUpdateCategoryTermMetaInputObjectTypeResolverTrait;

    public function getTypeName(): string
    {
        return 'RootUpdateGenericCategoryInput';
    }

    protected function isTaxonomyInputFieldMandatory(): bool
    {
        return false;
    }
}
