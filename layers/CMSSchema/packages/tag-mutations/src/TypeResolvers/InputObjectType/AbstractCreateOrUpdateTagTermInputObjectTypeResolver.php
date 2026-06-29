<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\TaxonomyMutations\Constants\MutationInputProperties;
use PoPCMSSchema\TaxonomyMutations\TypeResolvers\InputObjectType\AbstractCreateOrUpdateTaxonomyTermInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;

abstract class AbstractCreateOrUpdateTagTermInputObjectTypeResolver extends AbstractCreateOrUpdateTaxonomyTermInputObjectTypeResolver implements UpdateTagTermInputObjectTypeResolverInterface, CreateTagTermInputObjectTypeResolverInterface
{
    private ?TagByOneofInputObjectTypeResolver $parentTagByOneofInputObjectTypeResolver = null;

    final protected function getTagByOneofInputObjectTypeResolver(): TagByOneofInputObjectTypeResolver
    {
        if ($this->parentTagByOneofInputObjectTypeResolver === null) {
            /** @var TagByOneofInputObjectTypeResolver */
            $parentTagByOneofInputObjectTypeResolver = $this->instanceManager->getInstance(TagByOneofInputObjectTypeResolver::class);
            $this->parentTagByOneofInputObjectTypeResolver = $parentTagByOneofInputObjectTypeResolver;
        }
        return $this->parentTagByOneofInputObjectTypeResolver;
    }

    protected function getTaxonomyTermParentInputObjectTypeResolver(): InputTypeResolverInterface
    {
        return $this->getTagByOneofInputObjectTypeResolver();
    }

    protected function addParentIDInputField(): bool
    {
        return true;
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to create or update a tag term', 'gatographql');
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            MutationInputProperties::ID => $this->__('The ID of the tag to update', 'gatographql'),
            MutationInputProperties::NAME => $this->__('The name of the tag', 'gatographql'),
            MutationInputProperties::DESCRIPTION => $this->__('The description of the tag', 'gatographql'),
            MutationInputProperties::SLUG => $this->__('The slug of the tag', 'gatographql'),
            MutationInputProperties::TAXONOMY => $this->__('The taxonomy of the tag', 'gatographql'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }
}
