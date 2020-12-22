<?php
use PoPSchema\PostTags\TypeResolvers\PostTagTypeResolver;

class PoP_PostTags_Module_Processor_FieldDataloads extends PoP_Tags_Module_Processor_FieldDataloads
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



