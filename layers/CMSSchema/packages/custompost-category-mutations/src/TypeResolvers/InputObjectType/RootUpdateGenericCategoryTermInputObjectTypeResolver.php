<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\CategoryMutations\TypeResolvers\InputObjectType\RootUpdateCategoryTermInputObjectTypeResolverTrait;

class RootUpdateGenericCategoryTermInputObjectTypeResolver extends AbstractCreateOrUpdateGenericCategoryTermInputObjectTypeResolver implements UpdateGenericCategoryTermInputObjectTypeResolverInterface
{
    use RootUpdateCategoryTermInputObjectTypeResolverTrait;

    public function getTypeName(): string
    {
        return 'RootUpdateGenericCategoryInput';
    }

    protected function isTaxonomyInputFieldMandatory(): bool
    {
        return false;
    }
}
