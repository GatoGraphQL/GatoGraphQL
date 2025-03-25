<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\CategoryMutations\TypeResolvers\InputObjectType\RootAddCategoryTermMetaInputObjectTypeResolverTrait;

class RootAddGenericCategoryTermMetaInputObjectTypeResolver extends AbstractCreateOrUpdateGenericCategoryTermMetaInputObjectTypeResolver implements AddGenericCategoryTermMetaInputObjectTypeResolverInterface
{
    use RootAddCategoryTermMetaInputObjectTypeResolverTrait;

    public function getTypeName(): string
    {
        return 'RootCreateGenericCategoryInput';
    }

    protected function isTaxonomyInputFieldMandatory(): bool
    {
        return true;
    }
}
