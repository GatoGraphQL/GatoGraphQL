<?php

declare(strict_types=1);

namespace PoPSchema\PostTags\ConditionalOnComponent\API\ModuleProcessors;

use PoPSchema\PostTags\TypeResolvers\ObjectType\PostTagObjectTypeResolver;
use PoPSchema\Tags\ConditionalOnComponent\API\ModuleProcessors\AbstractFieldDataloadModuleProcessor;

class PostTagFieldDataloadModuleProcessor extends AbstractFieldDataloadModuleProcessor
{
    public function getRelationalTypeResolver(array $module): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_TAG:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_TAGLIST:
                return PostTagObjectTypeResolver::class;
        }

        return parent::getRelationalTypeResolver($module);
    }
}
