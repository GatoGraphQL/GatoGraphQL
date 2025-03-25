<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\InputObjectType;

class RootUpdateGenericCategoryTermMetaInputObjectTypeResolver extends AbstractUpdateGenericCategoryTermMetaInputObjectTypeResolver implements UpdateGenericCategoryTermMetaInputObjectTypeResolverInterface
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
