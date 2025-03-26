<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\CategoryMetaMutations\TypeResolvers\InputObjectType\AbstractUpdateCategoryTermMetaInputObjectTypeResolver;

class GenericCategoryTermMetaUpdateInputObjectTypeResolver extends AbstractUpdateCategoryTermMetaInputObjectTypeResolver implements UpdateGenericCategoryTermMetaInputObjectTypeResolverInterface
{
    use CategoryTermMetaUpdateInputObjectTypeResolverTrait;

    public function getTypeName(): string
    {
        return 'GenericCategoryMetaUpdateInput';
    }

    protected function isTaxonomyInputFieldMandatory(): bool
    {
        return false;
    }
}
