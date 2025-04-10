<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMetaMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\MetaMutations\Constants\MutationInputProperties;
use PoPCMSSchema\MetaMutations\TypeResolvers\InputObjectType\AbstractAddEntityMetaInputObjectTypeResolver;

abstract class AbstractAddTaxonomyTermMetaInputObjectTypeResolver extends AbstractAddEntityMetaInputObjectTypeResolver implements AddTaxonomyTermMetaInputObjectTypeResolverInterface
{
    public function getTypeDescription(): ?string
    {
        return $this->__('Input to add meta to a taxonomy term', 'taxonomymeta-mutations');
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            MutationInputProperties::ID => $this->__('The ID of the taxonomy term', 'taxonomymeta-mutations'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }
}
