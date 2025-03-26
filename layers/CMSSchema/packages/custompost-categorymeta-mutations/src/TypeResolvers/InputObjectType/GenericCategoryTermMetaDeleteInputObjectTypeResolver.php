<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\CategoryMetaMutations\TypeResolvers\InputObjectType\AbstractDeleteCategoryTermMetaInputObjectTypeResolver;

class GenericCategoryTermMetaDeleteInputObjectTypeResolver extends AbstractDeleteCategoryTermMetaInputObjectTypeResolver implements DeleteGenericCategoryTermMetaInputObjectTypeResolverInterface
{
    use CategoryTermMetaDeleteInputObjectTypeResolverTrait;

    public function getTypeName(): string
    {
        return 'GenericCategoryMetaDeleteInput';
    }

    protected function isTaxonomyInputFieldMandatory(): bool
    {
        return false;
    }
}
