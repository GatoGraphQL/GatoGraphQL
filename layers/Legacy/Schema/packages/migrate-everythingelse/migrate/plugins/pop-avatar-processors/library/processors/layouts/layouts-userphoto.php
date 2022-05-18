<?php

class PoP_Module_Processor_UserPhotoLayouts extends PoP_Module_Processor_UserPhotoLayoutsBase
{
    public final const MODULE_LAYOUT_AUTHOR_USERPHOTO = 'layout-author-userphoto';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_AUTHOR_USERPHOTO],
        );
    }
}



