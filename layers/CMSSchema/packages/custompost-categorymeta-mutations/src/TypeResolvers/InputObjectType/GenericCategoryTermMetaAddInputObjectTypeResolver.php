<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\CategoryMutations\TypeResolvers\InputObjectType\CategoryTermMetaCreateInputObjectTypeResolverTrait;

class GenericCategoryTermMetaAddInputObjectTypeResolver extends AbstractCreateOrUpdateGenericCategoryTermMetaInputObjectTypeResolver implements AddGenericCategoryTermMetaInputObjectTypeResolverInterface
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
