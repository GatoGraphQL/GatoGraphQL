<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\InputObjectType;

class GenericCategoryTermMetaAddInputObjectTypeResolver extends AbstractAddGenericCategoryTermMetaInputObjectTypeResolver implements AddGenericCategoryTermMetaInputObjectTypeResolverInterface
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
