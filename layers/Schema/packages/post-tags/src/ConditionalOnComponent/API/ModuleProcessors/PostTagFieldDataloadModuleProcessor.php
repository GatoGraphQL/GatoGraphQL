<?php

declare(strict_types=1);

namespace PoPSchema\PostTags\ConditionalOnComponent\API\ModuleProcessors;

use PoPSchema\PostTags\TypeResolvers\PostTagTypeResolver;
use PoPSchema\Tags\ConditionalOnComponent\API\ModuleProcessors\AbstractFieldDataloadModuleProcessor;

class PostTagFieldDataloadModuleProcessor extends AbstractFieldDataloadModuleProcessor
{
    public function getRelationalTypeResolverClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_TAG:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_TAGLIST:
                return PostTagTypeResolver::class;
        }

        return parent::getRelationalTypeResolverClass($module);
    }
}
