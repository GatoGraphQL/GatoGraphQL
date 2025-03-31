<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMetaMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\MetaMutations\Constants\MutationInputProperties;
use PoPCMSSchema\TaxonomyMetaMutations\TypeResolvers\InputObjectType\AbstractDeleteTaxonomyTermMetaInputObjectTypeResolver;

abstract class AbstractDeleteCategoryTermMetaInputObjectTypeResolver extends AbstractDeleteTaxonomyTermMetaInputObjectTypeResolver implements DeleteCategoryTermMetaInputObjectTypeResolverInterface
{
    public function getTypeDescription(): ?string
    {
        return $this->__('Input to delete a category term\'s meta entry', 'categorymeta-mutations');
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            MutationInputProperties::ID => $this->__('The ID of the category', 'categorymeta-mutations'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }
}
