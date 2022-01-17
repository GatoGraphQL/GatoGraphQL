<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\TypeResolvers\ObjectType;

use PoPCMSSchema\Tags\TypeAPIs\TagTypeAPIInterface;
use PoPCMSSchema\Taxonomies\TypeResolvers\ObjectType\AbstractTaxonomyObjectTypeResolver;

abstract class AbstractTagObjectTypeResolver extends AbstractTaxonomyObjectTypeResolver implements TagObjectTypeResolverInterface
{
    abstract public function getTagTypeAPI(): TagTypeAPIInterface;

    public function getTypeDescription(): ?string
    {
        return $this->__('Representation of a tag, added to a custom post', 'tags');
    }

    public function getID(object $object): string | int | null
    {
        $tagTypeAPI = $this->getTagTypeAPI();
        $tag = $object;
        return $tagTypeAPI->getTagID($tag);
    }
}
