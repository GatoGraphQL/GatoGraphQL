<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\TaxonomyMetaMutations\Constants\MutationInputProperties;
use PoPCMSSchema\TaxonomyMetaMutations\TypeResolvers\InputObjectType\AbstractAddTaxonomyTermMetaInputObjectTypeResolver;

abstract class AbstractAddCategoryTermMetaInputObjectTypeResolver extends AbstractAddTaxonomyTermMetaInputObjectTypeResolver implements AddCategoryTermMetaInputObjectTypeResolverInterface
{
    public function getTypeDescription(): ?string
    {
        return $this->__('Input to add category meta', 'category-mutations');
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            MutationInputProperties::ID => $this->__('The ID of the category to delete', 'category-mutations'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }
}
