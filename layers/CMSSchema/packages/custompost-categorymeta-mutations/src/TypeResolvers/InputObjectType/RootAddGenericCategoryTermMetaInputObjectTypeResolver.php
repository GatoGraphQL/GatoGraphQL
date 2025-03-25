<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\InputObjectType;

class RootAddGenericCategoryTermMetaInputObjectTypeResolver extends AbstractAddGenericCategoryTermMetaInputObjectTypeResolver implements AddGenericCategoryTermMetaInputObjectTypeResolverInterface
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
