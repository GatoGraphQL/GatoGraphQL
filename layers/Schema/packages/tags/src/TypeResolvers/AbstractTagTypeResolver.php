<?php

declare(strict_types=1);

namespace PoPSchema\Tags\TypeResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\Tags\ComponentContracts\TagAPIRequestedContractTrait;
use PoPSchema\Taxonomies\TypeResolvers\AbstractTaxonomyTypeResolver;

abstract class AbstractTagTypeResolver extends AbstractTaxonomyTypeResolver
{
    use TagAPIRequestedContractTrait;

    public function getSchemaTypeDescription(): ?string
    {
        return $this->translationAPI->__('Representation of a tag, added to a custom post', 'tags');
    }

    public function getID(object $resultItem): string | int
    {
        $tagTypeAPI = $this->getTypeAPI();
        $tag = $resultItem;
        return $tagTypeAPI->getTagID($tag);
    }
}
