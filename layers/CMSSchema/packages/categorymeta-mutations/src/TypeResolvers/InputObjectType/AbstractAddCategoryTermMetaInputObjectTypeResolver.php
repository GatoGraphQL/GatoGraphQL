<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMetaMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\MetaMutations\Constants\MutationInputProperties;
use PoPCMSSchema\TaxonomyMetaMutations\TypeResolvers\InputObjectType\AbstractAddTaxonomyTermMetaInputObjectTypeResolver;

abstract class AbstractAddCategoryTermMetaInputObjectTypeResolver extends AbstractAddTaxonomyTermMetaInputObjectTypeResolver implements AddCategoryTermMetaInputObjectTypeResolverInterface
{
    public function getTypeDescription(): ?string
    {
        return $this->__('Input to add meta to a category term', 'categorymeta-mutations');
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            MutationInputProperties::ID => $this->__('The ID of the category', 'categorymeta-mutations'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }
}
