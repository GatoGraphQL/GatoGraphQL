<?php

class PoP_Module_Processor_ShareMultiples extends PoP_Module_Processor_MultiplesBase
{
    public const MODULE_MULTIPLE_EMBED = 'multiple-embed';
    public const MODULE_MULTIPLE_API = 'multiple-api';
    public const MODULE_MULTIPLE_COPYSEARCHURL = 'multiple-copysearchurl';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MULTIPLE_EMBED],
            [self::class, self::MODULE_MULTIPLE_API],
            [self::class, self::MODULE_MULTIPLE_COPYSEARCHURL],
        );
    }
    
    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_MULTIPLE_EMBED:
                $ret[] = [PoP_Module_Processor_ShareContents::class, PoP_Module_Processor_ShareContents::MODULE_CONTENT_EMBED];
                $ret[] = [PoP_Module_Processor_ShareContents::class, PoP_Module_Processor_ShareContents::MODULE_CONTENT_EMBEDPREVIEW];
                break;

            case self::MODULE_MULTIPLE_API:
                $ret[] = [PoP_Module_Processor_ShareContents::class, PoP_Module_Processor_ShareContents::MODULE_CONTENT_API];
                $ret[] = [PoP_Module_Processor_ShareContents::class, PoP_Module_Processor_ShareContents::MODULE_CONTENT_EMBEDPREVIEW];
                break;

            case self::MODULE_MULTIPLE_COPYSEARCHURL:
                $ret[] = [PoP_Module_Processor_ShareContents::class, PoP_Module_Processor_ShareContents::MODULE_CONTENT_COPYSEARCHURL];
                break;
        }
    
        return $ret;
    }
}



