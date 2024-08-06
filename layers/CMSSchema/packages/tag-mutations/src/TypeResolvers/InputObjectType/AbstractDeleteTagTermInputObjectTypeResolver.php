<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\TaxonomyMutations\Constants\MutationInputProperties;
use PoPCMSSchema\TaxonomyMutations\TypeResolvers\InputObjectType\AbstractDeleteTaxonomyTermInputObjectTypeResolver;

abstract class AbstractDeleteTagTermInputObjectTypeResolver extends AbstractDeleteTaxonomyTermInputObjectTypeResolver implements UpdateTagTermInputObjectTypeResolverInterface, CreateTagTermInputObjectTypeResolverInterface
{
    public function getTypeDescription(): ?string
    {
        return $this->__('Input to delete a tag term', 'tag-mutations');
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            MutationInputProperties::ID => $this->__('The ID of the tag to delete', 'tag-mutations'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }
}
