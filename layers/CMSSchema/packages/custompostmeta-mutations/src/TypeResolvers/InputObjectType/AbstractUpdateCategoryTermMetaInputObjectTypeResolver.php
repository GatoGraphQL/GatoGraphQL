<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMetaMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\MetaMutations\Constants\MutationInputProperties;
use PoPCMSSchema\TaxonomyMetaMutations\TypeResolvers\InputObjectType\AbstractUpdateTaxonomyTermMetaInputObjectTypeResolver;

abstract class AbstractUpdateCategoryTermMetaInputObjectTypeResolver extends AbstractUpdateTaxonomyTermMetaInputObjectTypeResolver implements UpdateCategoryTermMetaInputObjectTypeResolverInterface
{
    public function getTypeDescription(): ?string
    {
        return $this->__('Input to update a category term\'s meta', 'categorymeta-mutations');
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            MutationInputProperties::ID => $this->__('The ID of the category', 'categorymeta-mutations'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }
}
