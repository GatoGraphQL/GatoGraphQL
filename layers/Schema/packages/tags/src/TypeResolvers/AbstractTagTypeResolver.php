<?php

declare(strict_types=1);

namespace PoPSchema\Tags\TypeResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use PoPSchema\Tags\ComponentContracts\TagAPIRequestedContractTrait;

abstract class AbstractTagTypeResolver extends AbstractTypeResolver
{
    use TagAPIRequestedContractTrait;

    public function getSchemaTypeDescription(): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return $translationAPI->__('Representation of a tag, added to a custo post', 'tags');
    }

    public function getID(object $resultItem): string | id
    {
        $cmstagsresolver = $this->getObjectPropertyAPI();
        $tag = $resultItem;
        return $cmstagsresolver->getTagID($tag);
    }
}
