<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\CategoryMutations\TypeResolvers\InputObjectType\RootCreateCategoryTermMetaInputObjectTypeResolverTrait;

class RootCreateGenericCategoryTermMetaInputObjectTypeResolver extends AbstractCreateOrUpdateGenericCategoryTermMetaInputObjectTypeResolver implements CreateGenericCategoryTermMetaInputObjectTypeResolverInterface
{
    use RootCreateCategoryTermMetaInputObjectTypeResolverTrait;

    public function getTypeName(): string
    {
        return 'RootCreateGenericCategoryInput';
    }

    protected function isTaxonomyInputFieldMandatory(): bool
    {
        return true;
    }
}
