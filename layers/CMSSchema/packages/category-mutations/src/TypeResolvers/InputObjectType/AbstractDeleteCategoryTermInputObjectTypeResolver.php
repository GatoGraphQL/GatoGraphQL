<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\TaxonomyMutations\Constants\MutationInputProperties;
use PoPCMSSchema\TaxonomyMutations\TypeResolvers\InputObjectType\AbstractDeleteTaxonomyTermInputObjectTypeResolver;

abstract class AbstractDeleteCategoryTermInputObjectTypeResolver extends AbstractDeleteTaxonomyTermInputObjectTypeResolver implements UpdateCategoryTermInputObjectTypeResolverInterface, CreateCategoryTermInputObjectTypeResolverInterface
{
    public function getTypeDescription(): ?string
    {
        return $this->__('Input to delete a category term', 'category-mutations');
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            MutationInputProperties::ID => $this->__('The ID of the category to delete', 'category-mutations'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }
}
