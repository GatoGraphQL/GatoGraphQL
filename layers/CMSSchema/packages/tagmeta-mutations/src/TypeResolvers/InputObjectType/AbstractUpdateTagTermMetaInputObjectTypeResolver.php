<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMetaMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\MetaMutations\Constants\MutationInputProperties;
use PoPCMSSchema\TaxonomyMetaMutations\TypeResolvers\InputObjectType\AbstractUpdateTaxonomyTermMetaInputObjectTypeResolver;

abstract class AbstractUpdateTagTermMetaInputObjectTypeResolver extends AbstractUpdateTaxonomyTermMetaInputObjectTypeResolver implements UpdateTagTermMetaInputObjectTypeResolverInterface
{
    public function getTypeDescription(): ?string
    {
        return $this->__('Input to update a tag term\'s meta', 'gatographql');
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            MutationInputProperties::ID => $this->__('The ID of the tag', 'gatographql'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }
}
