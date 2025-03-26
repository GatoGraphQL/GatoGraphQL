<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\CategoryMetaMutations\TypeResolvers\InputObjectType\AbstractAddCategoryTermMetaInputObjectTypeResolver;

class RootAddGenericCategoryTermMetaInputObjectTypeResolver extends AbstractAddCategoryTermMetaInputObjectTypeResolver implements AddGenericCategoryTermMetaInputObjectTypeResolverInterface
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
