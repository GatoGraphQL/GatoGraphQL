<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMetaMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\MetaMutations\Constants\MutationInputProperties;
use PoPCMSSchema\MetaMutations\TypeResolvers\InputObjectType\AbstractDeleteEntityMetaInputObjectTypeResolver;

abstract class AbstractDeleteTaxonomyTermMetaInputObjectTypeResolver extends AbstractDeleteEntityMetaInputObjectTypeResolver implements DeleteTaxonomyTermMetaInputObjectTypeResolverInterface
{
    public function getTypeDescription(): ?string
    {
        return $this->__('Input to delete a taxonomy term\'s meta entry', 'taxonomymeta-mutations');
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            MutationInputProperties::ID => $this->__('The ID of the taxonomy term', 'taxonomymeta-mutations'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }
}
