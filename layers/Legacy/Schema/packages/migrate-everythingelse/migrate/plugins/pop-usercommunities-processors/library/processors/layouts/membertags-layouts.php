<?php

class GD_URE_Module_Processor_MemberTagsLayouts extends GD_URE_Module_Processor_MemberTagsLayoutsBase
{
    public final const MODULE_URE_LAYOUTUSER_MEMBERTAGS = 'ure-layoutuser-membertags-nodesc';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_URE_LAYOUTUSER_MEMBERTAGS],
        );
    }
}


