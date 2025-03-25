<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\InputObjectType;

class GenericCategoryTermMetaUpdateInputObjectTypeResolver extends AbstractUpdateGenericCategoryTermMetaInputObjectTypeResolver implements UpdateGenericCategoryTermMetaInputObjectTypeResolverInterface
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
