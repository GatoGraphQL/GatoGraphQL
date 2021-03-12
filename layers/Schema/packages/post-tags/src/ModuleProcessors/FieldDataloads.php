<?php

declare(strict_types=1);

namespace PoPSchema\PostTags\ModuleProcessors;

use PoPSchema\PostTags\TypeResolvers\PostTagTypeResolver;

class FieldDataloads extends PoP_Tags_Module_Processor_FieldDataloads
{
    public function getTypeResolverClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_TAG:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_TAGLIST:
                return PostTagTypeResolver::class;
        }

        return parent::getTypeResolverClass($module);
    }
}
