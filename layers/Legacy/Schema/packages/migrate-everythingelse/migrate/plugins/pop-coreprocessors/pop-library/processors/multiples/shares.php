<?php

class PoP_Module_Processor_ShareMultiples extends PoP_Module_Processor_MultiplesBase
{
    public final const MODULE_MULTIPLE_EMBED = 'multiple-embed';
    public final const MODULE_MULTIPLE_API = 'multiple-api';
    public final const MODULE_MULTIPLE_COPYSEARCHURL = 'multiple-copysearchurl';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MULTIPLE_EMBED],
            [self::class, self::MODULE_MULTIPLE_API],
            [self::class, self::MODULE_MULTIPLE_COPYSEARCHURL],
        );
    }
    
    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        switch ($componentVariation[1]) {
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



