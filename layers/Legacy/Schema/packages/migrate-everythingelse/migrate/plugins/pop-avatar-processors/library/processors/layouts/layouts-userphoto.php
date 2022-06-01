<?php

class PoP_Module_Processor_UserPhotoLayouts extends PoP_Module_Processor_UserPhotoLayoutsBase
{
    public final const COMPONENT_LAYOUT_AUTHOR_USERPHOTO = 'layout-author-userphoto';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_AUTHOR_USERPHOTO,
        );
    }
}



