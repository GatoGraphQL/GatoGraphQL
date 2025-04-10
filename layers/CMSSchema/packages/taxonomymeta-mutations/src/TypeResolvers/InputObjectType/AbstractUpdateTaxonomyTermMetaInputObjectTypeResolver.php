<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMetaMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\MetaMutations\Constants\MutationInputProperties;
use PoPCMSSchema\MetaMutations\TypeResolvers\InputObjectType\AbstractUpdateEntityMetaInputObjectTypeResolver;

abstract class AbstractUpdateTaxonomyTermMetaInputObjectTypeResolver extends AbstractUpdateEntityMetaInputObjectTypeResolver implements UpdateTaxonomyTermMetaInputObjectTypeResolverInterface
{
    public function getTypeDescription(): ?string
    {
        return $this->__('Input to update a taxonomy term\'s meta', 'taxonomymeta-mutations');
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            MutationInputProperties::ID => $this->__('The ID of the taxonomy term', 'taxonomymeta-mutations'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }
}
