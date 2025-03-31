<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMetaMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\MetaMutations\Constants\MutationInputProperties;
use PoPCMSSchema\TaxonomyMetaMutations\TypeResolvers\InputObjectType\AbstractSetTaxonomyTermMetaInputObjectTypeResolver;

abstract class AbstractSetTagTermMetaInputObjectTypeResolver extends AbstractSetTaxonomyTermMetaInputObjectTypeResolver implements SetTagTermMetaInputObjectTypeResolverInterface
{
    public function getTypeDescription(): ?string
    {
        return $this->__('Input to set a tag term\'s meta entries', 'tagmeta-mutations');
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            MutationInputProperties::ID => $this->__('The ID of the tag', 'tagmeta-mutations'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }
}
