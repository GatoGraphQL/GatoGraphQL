<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTags\ConditionalOnModule\API\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPCMSSchema\Tags\ConditionalOnModule\API\ComponentProcessors\AbstractFieldDataloadComponentProcessor;

class PostTagFieldDataloadComponentProcessor extends AbstractFieldDataloadComponentProcessor
{
    public function getRelationalTypeResolver(Component $component): ?RelationalTypeResolverInterface
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_TAG:
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_TAGLIST:
                return $this->getPostTagObjectTypeResolver();
        }

        return parent::getRelationalTypeResolver($component);
    }
}
