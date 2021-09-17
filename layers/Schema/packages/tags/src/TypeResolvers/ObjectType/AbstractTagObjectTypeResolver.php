<?php

declare(strict_types=1);

namespace PoPSchema\Tags\TypeResolvers\ObjectType;

use PoPSchema\Tags\ComponentContracts\TagAPIRequestedContractTrait;
use PoPSchema\Taxonomies\TypeResolvers\ObjectType\AbstractTaxonomyObjectTypeResolver;

abstract class AbstractTagObjectTypeResolver extends AbstractTaxonomyObjectTypeResolver implements TagObjectTypeResolverInterface
{
    use TagAPIRequestedContractTrait;

    public function getSchemaTypeDescription(): ?string
    {
        return $this->translationAPI->__('Representation of a tag, added to a custom post', 'tags');
    }

    public function getID(object $object): string | int | null
    {
        $tagTypeAPI = $this->getTagTypeAPI();
        $tag = $object;
        return $tagTypeAPI->getTagID($tag);
    }
}
