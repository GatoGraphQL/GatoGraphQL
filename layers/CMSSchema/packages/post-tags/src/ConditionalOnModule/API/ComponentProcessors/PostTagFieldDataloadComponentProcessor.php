<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTags\ConditionalOnModule\API\ComponentProcessors;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPCMSSchema\Tags\ConditionalOnModule\API\ComponentProcessors\AbstractFieldDataloadComponentProcessor;

class PostTagFieldDataloadComponentProcessor extends AbstractFieldDataloadComponentProcessor
{
    public function getRelationalTypeResolver(array $component): ?RelationalTypeResolverInterface
    {
        switch ($component[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_TAG:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_TAGLIST:
                return $this->getPostTagObjectTypeResolver();
        }

        return parent::getRelationalTypeResolver($component);
    }
}
