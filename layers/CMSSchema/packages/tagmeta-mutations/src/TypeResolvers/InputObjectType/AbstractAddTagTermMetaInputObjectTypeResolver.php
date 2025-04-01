<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMetaMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\MetaMutations\Constants\MutationInputProperties;
use PoPCMSSchema\TaxonomyMetaMutations\TypeResolvers\InputObjectType\AbstractAddTaxonomyTermMetaInputObjectTypeResolver;

abstract class AbstractAddTagTermMetaInputObjectTypeResolver extends AbstractAddTaxonomyTermMetaInputObjectTypeResolver implements AddTagTermMetaInputObjectTypeResolverInterface
{
    public function getTypeDescription(): ?string
    {
        return $this->__('Input to add meta to a tag term', 'tagmeta-mutations');
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            MutationInputProperties::ID => $this->__('The ID of the tag', 'tagmeta-mutations'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }
}
