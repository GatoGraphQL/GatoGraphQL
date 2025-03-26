<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\CategoryMetaMutations\TypeResolvers\InputObjectType\AbstractUpdateCategoryTermMetaInputObjectTypeResolver;

class RootUpdateGenericCategoryTermMetaInputObjectTypeResolver extends AbstractUpdateCategoryTermMetaInputObjectTypeResolver implements UpdateGenericCategoryTermMetaInputObjectTypeResolverInterface
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
