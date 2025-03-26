<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\CategoryMetaMutations\TypeResolvers\InputObjectType\AbstractAddCategoryTermMetaInputObjectTypeResolver;

class GenericCategoryTermMetaAddInputObjectTypeResolver extends AbstractAddCategoryTermMetaInputObjectTypeResolver implements AddGenericCategoryTermMetaInputObjectTypeResolverInterface
{
    use CategoryTermMetaAddInputObjectTypeResolverTrait;

    public function getTypeName(): string
    {
        return 'GenericCategoryMetaAddInput';
    }

    protected function isTaxonomyInputFieldMandatory(): bool
    {
        return false;
    }
}
