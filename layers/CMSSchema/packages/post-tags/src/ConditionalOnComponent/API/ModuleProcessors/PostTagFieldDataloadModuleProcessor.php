<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTags\ConditionalOnComponent\API\ModuleProcessors;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPCMSSchema\Tags\ConditionalOnComponent\API\ModuleProcessors\AbstractFieldDataloadModuleProcessor;

class PostTagFieldDataloadModuleProcessor extends AbstractFieldDataloadModuleProcessor
{
    public function getRelationalTypeResolver(array $module): ?RelationalTypeResolverInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_TAG:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_TAGLIST:
                return $this->getPostTagObjectTypeResolver();
        }

        return parent::getRelationalTypeResolver($module);
    }
}
