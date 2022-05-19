<?php

class PoP_Module_Processor_ShareMultiples extends PoP_Module_Processor_MultiplesBase
{
    public final const COMPONENT_MULTIPLE_EMBED = 'multiple-embed';
    public final const COMPONENT_MULTIPLE_API = 'multiple-api';
    public final const COMPONENT_MULTIPLE_COPYSEARCHURL = 'multiple-copysearchurl';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_MULTIPLE_EMBED],
            [self::class, self::COMPONENT_MULTIPLE_API],
            [self::class, self::COMPONENT_MULTIPLE_COPYSEARCHURL],
        );
    }
    
    public function getSubcomponents(array $component): array
    {
        $ret = parent::getSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_MULTIPLE_EMBED:
                $ret[] = [PoP_Module_Processor_ShareContents::class, PoP_Module_Processor_ShareContents::COMPONENT_CONTENT_EMBED];
                $ret[] = [PoP_Module_Processor_ShareContents::class, PoP_Module_Processor_ShareContents::COMPONENT_CONTENT_EMBEDPREVIEW];
                break;

            case self::COMPONENT_MULTIPLE_API:
                $ret[] = [PoP_Module_Processor_ShareContents::class, PoP_Module_Processor_ShareContents::COMPONENT_CONTENT_API];
                $ret[] = [PoP_Module_Processor_ShareContents::class, PoP_Module_Processor_ShareContents::COMPONENT_CONTENT_EMBEDPREVIEW];
                break;

            case self::COMPONENT_MULTIPLE_COPYSEARCHURL:
                $ret[] = [PoP_Module_Processor_ShareContents::class, PoP_Module_Processor_ShareContents::COMPONENT_CONTENT_COPYSEARCHURL];
                break;
        }
    
        return $ret;
    }
}



